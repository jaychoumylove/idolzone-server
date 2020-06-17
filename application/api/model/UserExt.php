<?php

namespace app\api\model;

use app\api\service\Star as StarService;
use app\base\model\Base;
use app\base\service\Common;
use app\api\model\User as UserModel;
use think\Db;
use app\api\service\User;

class UserExt extends Base
{

    public static function setTime($uid, $index)
    {
        $item = self::get(['user_id' => $uid]);

        $leftTime = json_decode($item['left_time'], true);
        $leftTime[$index] = time();
        $leftTime = json_encode($leftTime);

        self::where(['user_id' => $uid])->update([
            'left_time' => $leftTime
        ]);
    }

    /**增加抽奖次数 */
    public static function addCount($uid, $max = 10)
    {
        $data = self::where('user_id', $uid)->field('lottery_count,lottery_time')->find();

        if ($data['lottery_count'] >= $max) {
            // 当前剩余次数大于上限 
            $remainCount = $data['lottery_count'];
        } else {
            // 加完之后的抽奖次数
            $remainCount = floor((time() - $data['lottery_time']) / 60) + $data['lottery_count'];

            if ($remainCount > $data['lottery_count']) {
                $remainCount = $remainCount > $max ? $max : $remainCount;

                self::where('user_id', $uid)->update([
                    'lottery_count' => $remainCount,
                    'lottery_time' => time(),
                ]);
            }
        }

        return $remainCount;
    }

    /**抽奖 */
    public static function lotteryStart($uid)
    {
        $data = self::where('user_id', $uid)->field('lottery_count,lottery_time,lottery_times')->find();
        if ($data['lottery_count'] <= 0) Common::res(['code' => 1, 'msg' => '没有抽奖次数了']);
        if ($data['lottery_times'] >= 100) Common::res(['code' => 1, 'msg' => '今天已经抽了100次了']);

        // 随机一个奖品
        $lottery = Common::lottery(CfgLottery::all());

        Db::startTrans();
        try {
            
            // 扣除金豆增加今日抽奖次数
            $isDone = self::where('user_id', $uid)->where('lottery_times', '<', 100)->update([
                'lottery_count' => Db::raw('lottery_count-1'),
                'lottery_times' => Db::raw('lottery_times+1'),
            ]);
            if(!$isDone) Common::res(['code' => 1, 'msg' => '今天已经抽了100次了']);
    
            RecTask::addRec($uid, [5, 6]);
            RecTaskfather::addRec($uid, [4, 15, 26, 37]);

            // if ($lottery['id'] == 3 || $lottery['id'] == 6) {
            //     // 抽中宝箱
            //     $lottery['rec_lottery_id'] = (int) RecLottery::create(['user_id' => $uid, 'lottery_id' => $lottery['id']])['id'];
            // }
    
            self::grant($uid, $lottery);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $lottery;
    }

    /**发放抽奖奖品 */
    public static function grant($uid, $lottery)
    {
        if ($lottery['type'] == 1) $type = 'coin';
        else if ($lottery['type'] == 2) $type = 'flower';
        else if ($lottery['type'] == 3) $type = 'stone';
        else if ($lottery['type'] == 4) $type = 'trumpet';

        (new User())->change($uid, [
            $type => $lottery['num']
        ], '幸运抽奖');

        //抽奖记录另存到一个表
        RecLottery::create([
            'user_id' => $uid,
            'lottery_id' => $lottery['id'],
            $type => $lottery['num']
        ]);
    }

    /**点赞 */
    public static function like($self, $other)
    {
        $dayLimit = 1;

        $thisday_like = self::where('user_id', $self)->value('thisday_like');
        if ($thisday_like >= $dayLimit) Common::res(['code' => 1, 'msg' => '今日点赞次数已用完']);

        Db::startTrans();
        try {
            self::where('user_id', $self)->update(['thisday_like' => Db::raw('thisday_like+1')]);
            UserStar::changeHandle($other, 'like');
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**增加福袋幸运值 */
    public static function addLucky($uid,$num,$task_id)
    {

        Db::startTrans();
        try {
            $lucky_value=self::where('user_id', $uid)->value('lucky_value');

            if($lucky_value>=100 || $lucky_value+$num>=100){
                self::where('user_id', $uid)->update([
                    'lucky_value' => 100,
                    'blessing_num' => Db::raw('blessing_num+'.$num)
                ]);
            }else{
                self::where('user_id', $uid)->update([
                    'lucky_value' => Db::raw('lucky_value+'.$num),
                    'blessing_num' => Db::raw('blessing_num+'.$num)
                ]);
            }

            if($task_id==4){
                RecTaskactivity618::addOrEdit($uid, $task_id, $num, $num);
            }else{
                RecTaskactivity618::addOrEdit($uid, $task_id, 0, $num);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**618活动福气榜列表 */
    public static function blessingList($uid,$page,$size)
    {
        $result['list']=self::field('send_blessing_num,user_id')->order('send_blessing_num desc')
            ->page($page, $size)->select();
        foreach ($result['list'] as $key=>$value){

            $value['user']=UserModel::where('id',$value['user_id'])->field('nickname,avatarurl')->find();
            $value['headwear']=HeadwearUser::getUse($value['user_id']);
            $value['level'] = CfgUserLevel::getLevel($uid);

            $result['list'][$key]=$value;
        }

        $my_send_blessing_info=self::where('user_id',$uid)->field('send_blessing_num,user_id')->find();
        $my_send_blessing_info['user']=UserModel::where('id',$my_send_blessing_info['user_id'])->field('id,nickname,avatarurl')->find();
        $my_send_blessing_info['headwear'] = HeadwearUser::getUse($my_send_blessing_info['user_id']);
        $my_send_blessing_info['level'] = CfgUserLevel::getLevel($uid);
        $send_blessing_members=self::order('send_blessing_num desc')->column('user_id');
        $result['myinfo']=$my_send_blessing_info;
        $result['myinfo']['rank']=array_search($uid,$send_blessing_members)+1;

        return $result;
    }

    /**使用福袋幸运值 */
    public static function useBlessingBag($starid, $hot, $uid, $type, $danmaku)
    {
        $myblessinginfo = self::where('user_id', $uid)->field('blessing_num,lucky_value')->find();
        if ($myblessinginfo['blessing_num'] == 0) Common::res(['code' => 1, 'msg' => '你暂时没有福袋了,快做任务获取吧']);
        $data=[
            ['id'=>1,'chance'=>50,'value'=>'6.18'],
            ['id'=>2,'chance'=>30,'value'=>'6.66'],
            ['id'=>3,'chance'=>20,'value'=>'8.88'],
            ['id'=>4,'chance'=>$myblessinginfo['lucky_value'],'value'=>'18']
        ];

        // 随机一个奖品
        if($myblessinginfo['lucky_value']==100){
            $lottery =['id'=>4,'chance'=>$myblessinginfo['lucky_value'],'value'=>'18'];
        }else{
            $lottery = Common::lottery($data);
        }

        $extraAdd = ceil(($lottery['value']*$hot)/100);
        if ($extraAdd <= 0) Common::res(['code' => 1, 'msg' => '网络错误']);

        Db::startTrans();
        try {

            self::where('user_id', $uid)->update([
                'blessing_num' => Db::raw('blessing_num-1'),
                'send_blessing_num' => Db::raw('send_blessing_num+'.$extraAdd),

            ]);

            RecActivity618::addRec([
                'user_id' => $uid,
                'content' => '你使用了1个福袋',
                'blessing_num' => -1,
            ]);

            (new StarService())->sendHot($starid, $extraAdd, $uid, $type, $danmaku,true);


            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }


        return [
            'addNum'=>$extraAdd,
            'value'=>$lottery['value'],
        ];
    }
}
