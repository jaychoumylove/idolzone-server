<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class FarmHelp extends Base
{
    public function helper()
    {
        return $this->belongsTo('User', 'helper', 'id');
    }

    public static function helpspeed($user_id, $helper)
    {
        $help_max = 5;

        // 我的今日助力次数是否已达上限
        $count = self::where('helper', $helper)->whereTime('create_time', 'd')->count('id');
        $res['remain'] = $help_max - $count;
        $res['award'] = 1000;
        if ($count >= $help_max) {
            Common::res(['data' => [
                'msg' => '你今日已经帮不同好友加速5次，帮助次数已达上限，请明日再来',
                'res' => $res,
                'status' => 1,
            ]]);
        }

        // 他今日是否已使用过加速
        $speedTime = UserSprite::where('user_id', $user_id)->value('speed_end');
        if (date('Ymd', $speedTime) == date('Ymd')) {
            Common::res(['data' => [
                'msg' => '他今日已经使用过加速了，请明日再来',
                'res' => $res,
                'status' => 1,
            ]]);
        }

        // 满8人助力失败
        $helperCount = self::where('user_id', $user_id)->whereTime('create_time', 'd')->count('id');
        if ($helperCount >= 8) {
            Common::res(['data' => [
                'msg' => '他的加速人数已满8人，你可以去帮助其他好友加速',
                'res' => $res,
                'status' => 1,
            ]]);
        }

        // 今日是否已帮她助力
        $isHelp = self::where('helper', $helper)->where('user_id', $user_id)->whereTime('create_time', 'd')->value('id');
        if ($isHelp) {
            Common::res(['data' => [
                'msg' => '你今天已帮这位好友加速过，请明日再来',
                'res' => $res,
                'status' => 1,
            ]]);
        }

        self::create([
            'user_id' => $user_id,
            'helper' => $helper,
        ]);

        (new User)->change($helper, ['coin' => $res['award']], '加速助力');
        $res['remain']--;

        Common::res(['data' => [
            'msg' => '帮助好友加速成功，金豆+' . $res['award'],
            'res' => $res,
            'status' => 0,
        ]]);
    }

    public static function helpstart($uid)
    {
        // 今日是否已加速
        $leastSpeedEnd = UserSprite::where('user_id', $uid)->whereTime('speed_end','d')->count();
        if ($leastSpeedEnd) Common::res(['code' => 1, 'msg' => '今日已加速，请明天再来']);

        // 2个好友以上才能开启加速
        $helperCount = FarmHelp::where('user_id', $uid)->whereTime('create_time', 'd')->count();
        if ($helperCount < 2) Common::res(['code' => 1, 'msg' => '至少需要2人助力']);

        // 奖励直接给
        $farmProduce = UserSprite::where('user_id', $uid)->value('total_speed_coin');

        $helpCount = FarmHelp::where('user_id', $uid)->whereTime('create_time', 'd')->count('id');
        if ($helpCount > 8) $helpCount = 8;
        $count = 10 * $helpCount * $farmProduce;

        Db::startTrans();
        try {
            $isDone = UserSprite::where('user_id', $uid)->whereTime('speed_end','<', 'today')->update([
                'speed_end' => time()
            ]);
            
            if(!$isDone) Common::res(['code' => 1, 'msg' => '今日已加速，请明天再来']);
            
            (new User)->change($uid, ['coin' => $count], '农场加速');
            
            RecTaskfather::addRec($uid, [6, 17, 28, 39]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 1, 'msg' => $e->getMessage()]);
        }

        Common::res(['code' => 0, 'msg' => '加速成功', 'data' => $count]);
    }
}
