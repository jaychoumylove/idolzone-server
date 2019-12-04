<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class NewboySignin extends Base
{
    /**进度 */
    public static function getProgress($uid)
    {
        return self::where('user_id', $uid)->value('days');
    }

    /**领奖励 */
    public static function getSettle($uid, $awards)
    {
        $data = self::where('user_id', $uid)->find();
        if ($data['days'] >= count($awards)) {
            Common::res(['code' => 1, 'msg' => '你已完成全部任务']);
        }
        if (date('Ymd', strtotime($data['update_time'])) == date('Ymd')) {
            Common::res(['code' => 1, 'msg' => '今日已领取奖励，请明天再来~']);
        }
        Db::startTrans();
        try {
            if (!$data) {
                $days = 1; // 第一天签到
                self::create(['user_id' => $uid, 'days' => $days]);
            } else {
                $days = $data['days'] + 1;
                self::where('user_id', $uid)->update(['days' => $days]);
            }

            (new User)->change($uid, $awards[$days - 1], '登录奖励');
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
