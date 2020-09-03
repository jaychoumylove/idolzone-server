<?php

namespace app\api\model;

use app\api\service\Star as StarService;
use app\base\model\Base;
use app\base\service\Common;
use app\api\model\User as UserModel;
use think\Db;
use app\api\service\User;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

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
    public static function addCount($uid, $type = 0)
    {
        if ($type == 0) {
            $data = (new self())->readMaster()
                ->where('user_id', $uid)
                ->field('lottery_count,lottery_time,lottery_times')
                ->find();
        } else {
            $data = self::where('user_id', $uid)
                ->field('lottery_count,lottery_time,lottery_times')
                ->find();
        }

        $config = Cfg::getCfg(Cfg::FREE_LOTTERY);
        if ($data['lottery_times'] >= $config['day_max']) {
            return 0;
        }
        $diff = (int)bcsub(time(), $data['lottery_time']);
        // 剩余次数
        $leftTimes = bcsub($config['day_max'], $data['lottery_times']);
        $auto_add_time = $config['auto_add_time'];
        if ($diff < $auto_add_time) {
            // 不在自动恢复次数时间内
            return $data['lottery_count'] > $leftTimes ? $leftTimes: $data['lottery_count'];
        }

        $max = $config['add_max'];
        if ($type == 0) {
            $lv = CfgUserLevel::getLevel($uid);
            foreach ($config['level_max'] as $lvItem) {
                if ($lv >= $lvItem['level']) {
                    $max = $lvItem['max'];
                }
            }
        }
        if ($data['lottery_count'] >= $max) {
            // 当前剩余次数大于上限
            return $data['lottery_count'] > $leftTimes ? $leftTimes: $data['lottery_count'];
        }

        // 加完之后的抽奖次数
        $num = (int) bcdiv($diff, $auto_add_time);
        $remainCount = (int) bcadd($num, $data['lottery_count']);

        $remainCount = $remainCount > $max ? $max : $remainCount;

        self::where('user_id', $uid)->update([
            'lottery_count' => $remainCount,
            'lottery_time' => time(),
        ]);

        return $remainCount > $leftTimes ? $leftTimes: $remainCount;
    }

    /**抽奖 */
    public static function lotteryStart($uid)
    {
        $data = self::where('user_id', $uid)->field('lottery_count,lottery_time,lottery_times,lottery_star_time')->find();
        if ($data['lottery_count'] <= 0) Common::res(['code' => 1, 'msg' => '没有抽奖次数了']);
        $config = Cfg::getCfg(Cfg::FREE_LOTTERY);
        if ($data['lottery_times'] >= $config['day_max']) {
            $msg = sprintf('今天已经抽了%s次了', $config['day_max']);
            Common::res(['code' => 1, 'msg' => $msg]);
        }
        $dayTimes = bcadd($data['lottery_times'], 1);
        if ($dayTimes > $config['day_max']) {
            Common::res(['code' => 1, 'msg' => "抽奖次数不够"]);
        }
        $goMultiple = 0;
        if (array_key_exists('multiple', $config)) {
            $level = CfgUserLevel::getLevel($uid);
            foreach ($config['multiple'] as $item) {
                if ($level >= $item['level']) {
                    $goMultiple = 0;
                    if ($data['lottery_count'] >= $item['number']) {
                        $goMultiple = (int)$item['number'];
                    }
                }
            }
        }
        if ($goMultiple > 0) {
            $leftTimes = bcsub($config['day_max'], $data['lottery_times']);
            if ($leftTimes > $goMultiple) {
                Common::res(['code' => 1, 'msg' => "请点击 $goMultiple 抽吧"]);
            }
        }
        $currentTime = time();
        $diff = bcsub($currentTime, $data['lottery_star_time']);
        if ((int)$diff < (int)$config['start_limit_time']) {
            Common::res(['code' => 1, 'msg' => "点击太快了"]);
        }

        // 随机一个奖品
        $lottery = Common::lottery(CfgLottery::all());

        Db::startTrans();
        try {
            // 扣除金豆增加今日抽奖次数
            $isDone = self::where('user_id', $uid)->where('lottery_times', '<', 100)->update([
                'lottery_count' => Db::raw('lottery_count-1'),
                'lottery_times' => Db::raw('lottery_times+1'),
                'lottery_star_time' => $currentTime
            ]);
            if(!$isDone) {
                $msg = isset($msg) ? $msg: sprintf('今天已经抽了%s次了', $config['day_max']);
                Common::res(['code' => 1, 'msg' => $msg]);
            }
    
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
        $list=self::field('send_blessing_num,user_id')->order('send_blessing_num desc')
            ->page($page, $size)->select();
        $list = json_decode(json_encode($list),TRUE);

        foreach ($list as &$value){
            $value['user']=UserModel::where('id',$value['user_id'])->field('id,nickname,avatarurl')->find();
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
            $value['user']['level'] = CfgUserLevel::getLevel($value['user_id']);
        }

        $result['list']=$list;

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

    /**增加福袋幸运值 */
    public static function luckyChange($uid,$num)
    {
        $user = self::where('user_id', $uid)->find();

        $lucky = (float)$user['lucky'];
        $max = RecWealActivityTask::WEAL_ACTIVE_EXTRA_PERCENT;

        if ($lucky >= $max) return true;

        $sum = bcadd ($lucky, $num, 2);

        $updated = self::where(['user_id' => $uid])->update(['lucky' => $sum]);

        return (bool)$updated;
    }

    /**
     * 更新送出的额外数据
     *
     * @param $uid
     * @param $extraHot
     * @param $starId
     * @return bool
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function extraHot($uid, $extraHot, $starId)
    {
        $userExt = UserExt::where('user_id', $uid)->find ();
        $sendHot = bcadd ($userExt['send_weal_hot'], $extraHot);

        $updated = UserExt::where('user_id', $uid)->update(['send_weal_hot' => $sendHot]);

        if (false == (bool) $updated) {
            return false;
        }

        self::extraHotLog ($uid, $extraHot, $starId);

        return true;
    }

    /**
     * @param $uid
     * @param $extraHot
     * @param $starId
     * @throws Exception
     * @throws DbException
     */
    public static function extraHotLog($uid, $extraHot, $starId)
    {
        $userCurrency = UserCurrency::get (['uid' => $uid]);

        $update = [
            'point' => bcadd ($userCurrency['point'], $extraHot)
        ];

        UserCurrency::where (['uid' => $uid])->update ($update);

        if (empty($starId)) {
            $star = Star::getByUser($uid);
        } else {
            $star = Star::get ($starId);
        }
        if (empty($star)) {
            throw new Exception('请加入圈子');
        }

        $insertRec = [
            'user_id'        => $uid,
            'content'        => sprintf ('夏日福袋,赠送【%s】额外人气+%s', $star['name'], $extraHot),
            'coin'           => 0,
            'flower'         => 0,
            'stone'          => 0,
            'trumpet'        => 0,
            'point'          => $extraHot,
            'before_coin'    => $userCurrency['coin'],
            'before_flower'  => $userCurrency['flower'],
            'before_stone'   => $userCurrency['stone'],
            'before_trumpet' => $userCurrency['trumpet'],
            'before_point'   => $userCurrency['point'],
        ];

        Rec::addRec ($insertRec);
    }

    /**618活动福气榜列表 */
    public static function luckyRank($uid,$page,$size)
    {
        $list=self::field('send_weal_hot,user_id')->order('send_weal_hot desc')
            ->page($page, $size)->select();
        $list = json_decode(json_encode($list),TRUE);

        $userIds = array_column ($list, 'user_id');
        array_push ($userIds, $uid);
        $userIds = array_unique ($userIds);
        $userIds = array_values ($userIds);

        $users = UserModel::where('id', 'in', $userIds)->field('id,nickname,avatarurl')->select();
        if (is_object ($users)) $users = $users->toArray ();
        $userDict = array_column ($users, null, 'id');

        $headWears = HeadwearUser::where('uid', 'in', $userIds)
            ->where('end_time is NULL or end_time>="'.date('Y-m-d H:i:s').'"')
            ->where('status', 1)
            ->field('uid,end_time,status')
            ->select ();
        if (is_object ($headWears)) $headWears = $headWears->toArray ();

        $headWearsDict = array_column ($headWears, 'hid', 'uid');
        $headWearsHids = array_unique ($headWearsDict);
        $headWearsHids = array_values ($headWearsHids);

        $cfgHeadWears = CfgHeadwear::where('id', 'in', $headWearsHids)->select ();
        if (is_object ($cfgHeadWears)) $cfgHeadWears = $cfgHeadWears->toArray ();

        $cfgHeadWearDict = array_column ($cfgHeadWears, null, 'id');

        foreach ($list as &$value){
            $value['user']= array_key_exists ($value['user_id'], $userDict) ? $userDict[$value['user_id']]: null;
            $value['user']['headwear'] = null;
            if (array_key_exists ($value['user_id'], $headWearsDict)) {
                if (array_key_exists ($headWearsDict[$value['user_id']], $cfgHeadWearDict)) {
                    $value['user']['headwear'] = $cfgHeadWearDict[$headWearsDict[$value['user_id']]];
                }
            }
            $value['user']['level'] = CfgUserLevel::getLevel($value['user_id']);
        }

        $result['list']=$list;

        $my_send_blessing_info= self::where ('user_id', $uid)->field('send_weal_hot,user_id')->find ();
        $my_send_blessing_info['user']= $userDict[$uid];
        $my_send_blessing_info['headwear'] = null;
        if (array_key_exists ($uid, $headWearsDict)) {
            if (array_key_exists ($headWearsDict[$uid], $cfgHeadWearDict)) {
                $my_send_blessing_info['headwear'] = $cfgHeadWearDict[$headWearsDict[$uid]];
            }
        }
        $my_send_blessing_info['level'] = CfgUserLevel::getLevel($uid);
        $send_blessing_members=self::order('send_weal_hot desc')->column('user_id');
        $result['myinfo']=$my_send_blessing_info;
        $result['myinfo']['rank']=array_search($uid,$send_blessing_members)+1;

        return $result;
    }

    /**使用福袋幸运值 */
    public static function useWealBag($starid, $hot, $uid, $type, $danmaku)
    {
        $myblessinginfo = self::where('user_id', $uid)->field('bag_num,lucky')->find();
        if ($myblessinginfo['bag_num'] == 0) Common::res(['code' => 1, 'msg' => '你暂时没有福袋了,快做任务获取吧']);
        $data=[
            ['id'=>1,'chance'=>50,'value'=>'6.18'],
            ['id'=>2,'chance'=>30,'value'=>'6.66'],
            ['id'=>3,'chance'=>20,'value'=>'8.88'],
            ['id'=>4,'chance'=>$myblessinginfo['lucky'],'value'=>'18']
        ];

        // 随机一个奖品
        if($myblessinginfo['lucky']==100){
            $lottery =['id'=>4,'chance'=>$myblessinginfo['lucky'],'value'=>'18'];
        }else{
            $lottery = Common::lottery($data);
        }

        $extraAdd = ceil(($lottery['value']*$hot)/100);
        if ($extraAdd <= 0) Common::res(['code' => 1, 'msg' => '网络错误']);

        Db::startTrans();
        try {

            self::where('user_id', $uid)->update([
                'bag_num' => Db::raw('bag_num-1'),
                'send_bag_num' => Db::raw('send_weal_hot+'.$extraAdd),

            ]);

            RecWealActivity::addRec([
                'user_id' => $uid,
                'content' => '你使用了1个福袋',
                'bag_num' => -1,
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

    public static function multipleLottery($type, $uid)
    {
        $data = self::where('user_id', $uid)->field('lottery_count,lottery_time,lottery_times,lottery_star_time')->find();
        if ($data['lottery_count'] <= 0) Common::res(['code' => 1, 'msg' => '没有抽奖次数了']);
        $config = Cfg::getCfg(Cfg::FREE_LOTTERY);
        if ($data['lottery_times'] >= $config['day_max']) {
            $msg = sprintf('今天已经抽了%s次了', $config['day_max']);
            Common::res(['code' => 1, 'msg' => $msg]);
        }
        $currentTime = time();
        $diff = bcsub($currentTime, $data['lottery_star_time']);
        if ((int)$diff < (int)$config['start_limit_time']) {
            Common::res(['code' => 1, 'msg' => "点击太快了"]);
        }

        $multipleType = array_filter($config['multiple'], function ($item) use ($type) {
            return $item['type'] == $type;
        });
        if (empty($multipleType)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }
        $multipleType = array_values($multipleType);
        $times = $multipleType[0]['number'];

//        $leftTimes = bcsub($config['day_max'], $data['lottery_times']);
//
//        if ($leftTimes < $times) {
//            $times = $leftTimes;
//        }
        $dayTimes = bcadd($data['lottery_times'], $times);
        if ($dayTimes > $config['day_max']) {
            Common::res(['code' => 1, 'msg' => '抽奖次数不够']);
        }
        $level = CfgUserLevel::getLevel($uid);
        if ($level < $multipleType[0]['level']) {
            Common::res(['code' => 1, 'msg' => '等级不够哦']);
        }
        if ($data['lottery_count'] < $times) {
            Common::res(['code' => 1, 'msg' => "抽奖次数不够 $times 哦"]);
        }

        // 随机一个奖品
        $lotteryList = CfgLottery::all();
        $array = range (0, bcsub ($times, 1));
        $choose = [];
        foreach ($array as $ite) {
            $chooseItem = Common::lottery ($lotteryList);
            if (is_object($chooseItem)) $chooseItem = $chooseItem->toArray();
            array_push ($choose, $chooseItem);
        }

        $returns = [];
        foreach ($choose as $key => $value) {
            if (array_key_exists($value['type'], $returns)) {
                $returns[$value['type']]['num'] = (int)bcadd($value['num'], $returns[$value['type']]['num']);
            } else {
                $returns[$value['type']] = $value;
            }
        }

        Db::startTrans();
        try {
            // 扣除金豆增加今日抽奖次数
            $isDone = self::where('user_id', $uid)->update([
                'lottery_count' => Db::raw('lottery_count-'.$times),
                'lottery_times' => Db::raw('lottery_times+'.$times),
                'lottery_star_time' => $currentTime
            ]);
            if(!$isDone) {
                $msg = sprintf('今天已经抽了%s次了', $times);
                Common::res(['code' => 1, 'msg' => $msg]);
            }

            RecTask::addRec($uid, [5, 6], $times);
            RecTaskfather::addRec($uid, [4, 15, 26, 37], $times);

            foreach ($choose as $item) {
                self::grant($uid, $item);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $returns;
    }
}
