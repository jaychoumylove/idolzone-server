<?php

namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserExt;
use app\base\service\WxMsg;
use app\base\controller\Base;
use think\Log;
use app\base\service\WxAPI;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg(input('appid'));

        $wxMsg->checkSignature();
        $msg = $wxMsg->getMsg();

        $content = "欢迎！回复：\n1 充值 \n2 农场补偿";

        // {"ToUserName":"gh_7c87eaf27f5a",
        // "FromUserName":"oj77y5LIpHuIWUU2kW8BHVP4goPc","CreateTime":"1558089549",
        // "MsgType":"text","Content":"99","MsgId":"22306477788296821"}

        if ($msg['MsgType'] == 'text') {
            if ($msg['Content'] == '农场补偿' || $msg['Content'] == 2) {
                $user_id = $wxMsg->getUser($msg['FromUserName']);
                if (gettype($user_id) != 'integer') {
                    $content = $user_id;
                } else {
                    $content = UserExt::redress($user_id);
                    $content .= '<a data-miniprogram-appid="wx3a69eb5e1b2a7fa9" data-miniprogram-path="/pages/user/log" href="http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=2247483657&idx=1&sn=f6fed458fdb14f16f8a0e035b874a462&chksm=cef49e9df983178b8fc49143703041fa2b1c230da13d4154b11403d1cd215f38892c16f3a0be#rd">点击此链接去查看</a>';
                }
            } else if ($msg['Content'] == '充值' || $msg['Content'] == 1) {
                $content = '<a href="https://idolzone.cyoor.com/#/pages/charge/charge">充值</a>';
            }
        }

        $wxMsg->autoSend($msg, 'text', [
            'Content' => $content
        ]);

        die('success');
    }

    public function createMenu()
    {
        $data = '{
            "button":[
            {
            "type": "miniprogram",
            "name": "打榜",
            "url": "https://mp.weixin.qq.com/s/V-Zw-FDPKLKY4GJfBdZS7w",
            "appid": "wx3a69eb5e1b2a7fa9",
            "pagepath": "/pages/index/index"
            },{
            "type": "view",
            "name": "充值",
            "url": "https://idolzone.cyoor.com/#/pages/charge/charge"
            }]
            }';

        dump((new WxAPI('gzh'))->createMenu($data));
    }
}
