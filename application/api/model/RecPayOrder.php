<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\service\User as UserService;

class RecPayOrder extends Base
{
    /**支付成功 处理业务 */
    public static function paySuccess($order)
    {
        $goodsInfo = json_decode($order['goods_info'], true);
        // 付款人
        $pay_uid = $order['user_id'];
        // 目标人
        $uid = $order['tar_user_id'];
        
        if(!$uid) $uid=$pay_uid;

        (new UserService)->change($uid, [
            'flower' => isset($goodsInfo['flower']) ? $goodsInfo['flower'] : 0,
            'stone' =>  isset($goodsInfo['stone']) ? $goodsInfo['stone'] : 0,
            'point' => $order['total_fee'] * 10000,
        ], '充值到账，付款人：' . User::where('id', $pay_uid)->value('nickname'));

        RecTask::addRec($uid, 7);

        RecTaskactivity618::addOrEdit($uid, 6, $order['total_fee']);

        RecWealActivityTask::setTask ($uid, (int)$order['total_fee'], CfgWealActivityTask::RECHARGE);

        RecUserPaid::setTask ($uid, (float)$order['total_fee'], CfgPaid::DAY);
        RecUserPaid::setTask ($uid, (float)$order['total_fee'], CfgPaid::SUM);

        if($pay_uid!=$uid) Rec::addRec(['user_id' => $pay_uid,'content' => '帮 ' . User::where('id', $uid)->value('nickname') . ' 充值成功']);
        
    }
}
