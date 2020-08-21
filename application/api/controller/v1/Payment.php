<?php

namespace app\api\controller\v1;

use app\base\service\alipay\request\AlipayTradeWapPayRequest;
use app\api\model\Cfg;
use app\base\controller\Base;
use app\base\service\alipay\AopClient;
use app\base\service\Common;
use app\api\model\PayGoods;
use app\api\model\RecPayOrder;
use app\base\service\WxAPI;
use app\api\model\User;
use app\api\model\UserSprite;
use app\base\service\WxPay as WxPayService;
use think\Db;
use think\Log;

class Payment extends Base
{
    /**商品列表 */
    public function goods()
    {
        $this->getUser();
        $res['list'] = PayGoods::all();
        // 我的优惠
        $res['discount'] = PayGoods::getMyDiscount($this->uid);

        // 农场产量
        $res['farm_coin'] = UserSprite::where('user_id', $this->uid)->value('total_speed_coin');
        $res['farm_distance'] = 432 - $res['farm_coin'];

        Common::res(['data' => $res]);
    }

    /**
     * 下单
     */
    public function order()
    {
        $this->getUser();
        if(User::where('id',$this->uid)->value('type')==5) Common::res(['code' => 1, 'msg' => '该账号检测不安全，不予以充值']);

        $type = $this->req('type', 'require');
        $user_id = $this->req('user_id', 'require',0);
        $count = $this->req('count', 'integer'); // 数目

        $discount = PayGoods::getMyDiscount($this->uid);
        if ($type == 'stone') {
            $rate = Cfg::getCfg('recharge_rate')['stone'];
            $totalFee = $count * $rate;
            if ($count < 100) Common::res(['code' => 1, 'msg' => '数值过小，需大于100颗']);
            $count *= $discount['increase'];
        } else if ($type == 'flower') {
            $rate = Cfg::getCfg('recharge_rate')['flower'];
            $totalFee = $count * $rate;
            if ($count < 1000000) Common::res(['code' => 1, 'msg' => '数值过小，需大于100万']);
            $count *= $discount['increase'];
        }

        // 下单
        $order = RecPayOrder::create([
            'id' => date('YmdHis') . mt_rand(1000, 9999),
            'user_id' => $this->uid,
            'tar_user_id' => $user_id ? $user_id : $this->uid,
            'total_fee' => $totalFee,
            'goods_info' => json_encode([$type => $count], JSON_UNESCAPED_UNICODE), // 商品信息
            'platform' => input('platform', null),
        ]);
        // 预支付参数
        $config = [
            'body' => '充值', // 支付标题
            'orderId' => $order['id'], // 订单ID
            'totalFee' => $totalFee, // 支付金额
            'notifyUrl' => 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1/pay/notify/' . input('platform'), // 支付成功通知url
            'tradeType' => 'JSAPI', // 支付类型
        ];
        // APP和小程序差异
        $openidType = 'openid';
        if (input('platform') == 'APP') {
            $openidType = 'openid_app';
            $config['tradeType'] = 'APP';
        } else if (input('platform') == 'MP-QQ') {
            $config['tradeType'] = 'MINIAPP';
        }

        $config['openid'] = User::where('id', $this->uid)->value($openidType);
        if (!$config['openid']) Common::res(['code' => 1, 'msg' => '请先登录小程序']);

        $res = (new WxAPI())->unifiedorder($config);

        // 处理预支付数据
        (new WxPayService())->returnFront($res);
    }

    public function alipayOrder()
    {
        $aop = new AopClient ();

        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '你的appid';
        $aop->rsaPrivateKey = '你的应用私钥';
        $aop->alipayrsaPublicKey = '你的支付宝公钥';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';

        $request = new AlipayTradeWapPayRequest ();
        $request->setBizContent("{" .
            "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," .
            "    \"subject\":\"测试\"," .
            "    \"out_trade_no\":\"70501111111S001111119\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":9.00," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        $result = $aop->pageExecute($request);
        echo $result;
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
