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
        $hid = self::where('uid', $uid)->where('end_time is NULL or end_time>="'.date('Y-m-d H:i:s').'"')->where('status', 1)->value('hid');
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
        $headWear = CfgHeadwear::where('id', $hid)->field('diamond,days')->find();
        
        Db::startTrans();
        try {
            // 扣钻石
            (new User)->change($uid, ['stone' => -$headWear['diamond']], '头饰购买');

            self::create([
                'uid' => $uid,
                'hid' => $hid,
                'end_time' => $headWear['days']>0 ? date('Y-m-d H:i:s',strtotime('+'.$headWear['days'].' day')) : NULL
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
