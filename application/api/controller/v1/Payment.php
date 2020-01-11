<?php

namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\PayGoods;
use app\api\model\RecPayOrder;
use app\base\service\WxAPI;
use app\api\model\User;
use app\base\service\WxPay as WxPayService;
use think\Db;
use app\api\model\Prop;
use think\Log;
use app\api\model\UserProp;

class Payment extends Base
{
    /**商品列表 */
    public function goods()
    {
        $this->getUser();
        $res['list'] = PayGoods::all();
        // 我的优惠
        $res['discount'] = PayGoods::getMyDiscount($this->uid);

        foreach ($res['list'] as &$value) {
            // 鲜花充值有折扣
            $value['fee'] = round($value['fee'] * $res['discount']['discount'], 2);
            $value['flower'] = round($value['flower'] * $res['discount']['flower_increase']);
            $value['stone'] = round($value['stone'] * $res['discount']['stone_increase']);
        }

        Common::res(['data' => $res]);
    }

    /**
     * 下单
     * @只能有一个商品，但可以多数量
     */
    public function order()
    {
        $this->getUser();
        $type = $this->req('type', 'require');
        $user_id = $this->req('user_id', 'require');
        $count = $this->req('count', 'integer');

        $res['discount'] = PayGoods::getMyDiscount($this->uid);
        if ($type == 'stone') {
            $rate = Cfg::getCfg('recharge_rate')['stone'];
            $fee = $count * $rate;
        }

        


        // 下单
        $order = RecPayOrder::create([
            'id' => date('YmdHis') . mt_rand(1000, 9999),
            'user_id' => $this->uid,
            'tar_user_id' => $user_id,
            'total_fee' => $totalFee,
            'goods_info' => json_encode($goods, JSON_UNESCAPED_UNICODE), // 商品信息
        ]);
        // 预支付参数
        $config = [
            'body' => $goods['title'], // 支付标题
            'orderId' => $order['id'], // 订单ID
            'totalFee' => $totalFee, // 支付金额
            'notifyUrl' => 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1/pay/notify', // 支付成功通知url
            'tradeType' => 'JSAPI', // 支付类型
        ];
        // APP和小程序差异
        $openidType = 'openid';
        if (input('platform') == 'APP') {
            $openidType = 'openid_app';
            $config['tradeType'] = 'APP';
        }

        $config['openid'] = User::where('id', $this->uid)->value($openidType);
        if (!$config['openid']) Common::res(['code' => 1, 'msg' => '请先登录小程序']);

        $res = (new WxAPI())->unifiedorder($config);

        // 处理预支付数据
        (new WxPayService())->returnFront($res);
    }

    /**支付成功的通知 */
    public function notify()
    {
        $wxPayService = new WxPayService();
        $data = $wxPayService->notifyHandle();
        $order = RecPayOrder::get($data['out_trade_no']);
        if ($data['total_fee'] == $order['total_fee'] * 100) {
            // 处理订单状态和业务
            Db::startTrans();
            try {
                // 更改订单状态
                $isDone = RecPayOrder::where(['id' => $data['out_trade_no']])->update(['pay_time' => $data['time_end']]);
                if ($isDone) {
                    // 支付成功 处理业务
                    RecPayOrder::paySuccess($order);
                    Db::commit();

                    $wxPayService->returnSuccess();
                }
            } catch (\Exception $e) {
                Db::rollback();
                Log::record($e->getMessage(), 'error');
            }
        }
    }
}
