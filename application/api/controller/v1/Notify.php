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
        $user_id = $wxMsg->getUser($msg['FromUserName']);
        Log::record('收到客服消息：' . json_encode($msg, JSON_UNESCAPED_UNICODE), 'error');

        // {"ToUserName":"gh_7c87eaf27f5a",
        // "FromUserName":"oj77y5LIpHuIWUU2kW8BHVP4goPc","CreateTime":"1558089549",
        // "MsgType":"text","Content":"99","MsgId":"22306477788296821"}

        if ($msg['MsgType'] == 'text') {
            if ($msg['Content'] == '农场补偿' || $msg['Content'] == 2 || $msg['Content'] == '2019') {
                $content = UserExt::redress($user_id);
                $content .= '<a data-miniprogram-appid="wx3a69eb5e1b2a7fa9" data-miniprogram-path="/pages/user/log" href="http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=2247483657&idx=1&sn=f6fed458fdb14f16f8a0e035b874a462&chksm=cef49e9df983178b8fc49143703041fa2b1c230da13d4154b11403d1cd215f38892c16f3a0be#rd">点击此链接去查看</a>';
            } else if ($msg['Content'] == '充值' || $msg['Content'] == 1) {
                $content = '<a href="https://idolzone.cyoor.com/#/pages/charge/charge">充值</a>';
            }
        } else if ($msg['MsgType'] == 'event') {
            if ($msg['EventKey'] == 'CLICK_kefu') {
                $content = " 【联系客服】\n您的用户ID为：$user_id\n请加客服（大白）微信：vpanfxcom\n请一定注明反馈的问题或者建议，否则可能会被忽略哦！";
            } else if ($msg['EventKey'] == 'CLICK_lianxi') {
                $content = " 【商务合作】\n寻求合作及赞助可发送邮件：alben.liu@qq.com\n请一定注明公司、姓名、以及合作内容、品牌，否则可能会被忽略哦！";
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
            "button": [
                {
                    "name": "打榜应援",
                    "sub_button": [
                        {
                            "type": "miniprogram",
                            "name": "小程序打榜",
                            "url": "https://mp.weixin.qq.com/s/V-Zw-FDPKLKY4GJfBdZS7w",
                            "appid": "wx3a69eb5e1b2a7fa9",
                            "pagepath":"pages/open/open"
                        },
                        {
                            "type": "view",
                            "name": "网页打榜",
                            "url": "https://idolzone.cyoor.com"
                        }
                    ]
                },
                {
                    "type": "view",
                    "name": "鲜花充值",
                    "url": "https://idolzone.cyoor.com/#/pages/charge/charge"
                },
                {
                    "name": "联系我们",
                    "sub_button": [
                        {
                            "type": "click",
                            "name": "在线客服",
                            "key": "CLICK_kefu"
                        },
                        {
                            "type": "click",
                            "name": "联系我们",
                            "key": "CLICK_lianxi"
                        }
                    ]
                }
            ]
        }';

        dump((new WxAPI('gzh'))->createMenu($data));
    }
}
