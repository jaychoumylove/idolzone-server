<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
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
            if(!$isDone) Common::res(['code' => 1, 'msg' => '今天已经抽了100次了2']);
    
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

    /**
     * 公众号补偿 
     */
    public static function redress($uid)
    {
        $redressDate = Cfg::getCfg('redress_date');
        $redressTime = UserExt::where('user_id', $uid)->value('redress_time');

        if ($redressTime > strtotime($redressDate[0]) && $redressTime < strtotime($redressDate[1])) return '你已领取过补偿';
        if (time() < strtotime($redressDate[0])) return '补偿未到时间';
        if (time() > strtotime($redressDate[1])) return '补偿已过期';

        $msg = '领取成功';
        $update['coin'] = 4708680;
        $msg .= '①数据回档任务等补偿:';
        $msg .= '，金豆+' . $update['coin'];
        $update['stone'] = 300;
        $msg .= '，钻石+' . $update['stone'];
        $update['flower'] = 150000;
        $msg .= '，鲜花+' . $update['flower'];
        (new User)->change($uid, $update, '①数据回档任务等补偿');        
        $msg .= "\n";
        
        $sprite = UserSprite::getInfo($uid);
        $update2['coin'] = $sprite['total_speed_coin']*3600*48/100;
        $msg .= '②农场补偿48小时:';
        $msg .= '金豆+' . $update2['coin'];
        (new User)->change($uid, $update2, '②数据回档农场补偿');
        $msg .= "\n";

        UserExt::where('user_id', $uid)->update(['redress_time' => time()]);

        return $msg;
    }

    /**公众号签到 */
    public static function gzhSignin($uid)
    {
        $isSignin = self::where('user_id', $uid)->whereTime('gzh_signin_time', 'd')->value('id');

        if ($isSignin) {
            $msg = '你今天已经签到了哦，请明日再来';
        } else {
            $msg = '签到成功';
            // 
            $update['coin'] = 3000;
            $msg .= '，金豆+' . $update['coin'];
            $update['stone'] = 3;
            $msg .= '，钻石+' . $update['stone'];
            $msg .= '，明天记得还要来哦';

            (new User)->change($uid, $update, '公众号签到');
            self::where('user_id', $uid)->update(['gzh_signin_time' => time()]);
        }

        return $msg;
    }
}
