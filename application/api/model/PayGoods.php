<?php

namespace app\api\model;

use think\Model;
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
        $data['discount'] = 0.8;
        $data['flower_increase'] = 1;
        $data['stone_increase'] = 1;
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
