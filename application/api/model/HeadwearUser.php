<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class HeadwearUser extends Base
{
    /**正在使用的头饰 */
    public static function getUse($uid)
    {
        $hid = self::where('uid', $uid)->where('status', 1)->value('hid');
        return CfgHeadwear::get($hid);
    }

    public static function cancel($uid)
    {
        self::where('uid', $uid)->update(['status' => 0]);
    }

    public static function use($uid, $hid)
    {
        self::cancel($uid);
        self::where('uid', $uid)->where('hid', $hid)->update(['status' => 1]);
    }

    public static function buy($uid, $hid)
    {
        // 需要多少钻石
        $needDiamond = CfgHeadwear::where('id', $hid)->value('diamond');

        Db::startTrans();
        try {
            // 扣钻石
            (new User)->change($uid, ['stone' => -$needDiamond], '头饰购买');

            self::create([
                'uid' => $uid,
                'hid' => $hid,
            ]);

            self::use($uid, $hid);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res(['msg' => '兑换成功']);
    }
}
