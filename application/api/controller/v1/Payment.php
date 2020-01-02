<?php

namespace app\api\controller\v1;

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
        $userprop_id = $this->req('userprop_id', 'integer', 0); // 优惠券id
        $res['list'] = PayGoods::where('1=1')->select();
        // 我的优惠
        $res['discount'] = PayGoods::getMyDiscount($this->uid, $userprop_id);

        // 可选优惠
        $giveResult = UserProp::giveRechargeTicketEveryday($this->uid);
        if (strpos($giveResult, '送') !== false) $res['modal'] = '送您一张双倍券，现在充值就享双倍鲜花哦';

        $arr[] = ['id' => 0, 'status' => 0, 'prop' => ['name' => '请选择优惠券'], 'title' => '不选择优惠券'];
        $data = UserProp::getList($this->uid, 'id asc', 'prop_id in (1,2)');
        array_walk($arr, function ($item) use (&$data) {
            array_unshift($data, $item);
        });
        $res['discount_option'] = $data;

        $tehui_show = false;
        foreach ($res['list'] as &$value) {
            if ($value['category'] > 0) $tehui_show = true;

            if ($value['remain'] < 0) {
                $value['remain'] = 0;
            }

            if ($value['category'] == 0) {
                // 鲜花充值有折扣
                $value['fee'] = round($value['fee'] * $res['discount']['discount'], 2);
                $value['flower'] = round($value['flower'] * $res['discount']['flower_increase']);
                $value['stone'] = round($value['stone'] * $res['discount']['stone_increase']);
            }
        }

        $res += ['tehui_show' => $tehui_show];
        Common::res(['data' => $res]);
    }

    /**
     * 下单
     * @只能有一个商品，但可以多数量
     */
    public function order()
    {
        $this->getUser();
        $goodsId = $this->req('goods_id', 'integer'); // 商品id
        $user_id = $this->req('user_id', 'integer'); // 充值目标用户
        $goodsNum = $this->req('goods_num', 'integer', 1); // 商品数量
        $userprop_id = $this->req('userprop_id', 'integer', 0); // 使用的优惠券

        // 商品
        $goods = PayGoods::get($goodsId);
        $goods['num'] = $goodsNum;
        $goods['user_id'] = $user_id;
        $totalFee = round($goods['fee'] * $goodsNum, 2);

        if ($goods['category'] == 0) {
            // 鲜花充值折扣
            $discount = PayGoods::getMyDiscount($this->uid, $userprop_id);
            $goods['flower'] = round($goods['flower'] * $discount['flower_increase']);
            $goods['stone'] = round($goods['stone'] * $discount['stone_increase']);
            $goods['userprop_id'] = $userprop_id;
            $totalFee = round($totalFee * $discount['discount'], 2);
            $goods['totalFee'] = $totalFee;
        }

        if ($goods['remain'] !== null && $goods['remain'] <= 0) {
            // 限量商品
            Common::res(['code' => 1, 'msg' => '抱歉，该商品已售完']);
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
