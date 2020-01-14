<?php

namespace app\api\model;

use app\api\service\Star as StarService;
use app\base\model\Base;
use app\base\service\Common;

class PayGoods extends Base
{
    public function Item()
    {
        return $this->belongsTo('CfgItem', 'item_id', 'id');
    }

    /**用户优惠 */
    public static function getMyDiscount($uid)
    {
        // 金额折扣
        $data['discount'] = 1;
        // 加成倍率
        $data['increase'] = 1;
        // 活动文字
        $data['text'] = '';

        $star_id = UserStar::getStarId($uid);
        $birth = (new StarService)->isTodayBrith($star_id);
        $sysIncrease = Cfg::getCfg('recharge_rate')['increase'];
        if ($birth) {
            // 明星生日福利，加成x2
            $data['increase'] = 2;
            $data['text'] = '生日x2';
        } else if ($sysIncrease > 1) {
            // 系统活动
            $data['increase'] = $sysIncrease;
            $data['text'] = 'x' . $sysIncrease;
        }

        return $data;
    }

    /**
     * 生成商品信息
     * @param int $type 0礼物 1道具
     */
    public static function getInfo($uid, $id, $num, $type)
    {
        $data = self::get($id);

        if (!$data) Common::res(['code' => 303]);
        $data['user_id'] = $uid;
        $data['num'] = $num;
        $data['type'] = $type;

        return $data;
    }
}
