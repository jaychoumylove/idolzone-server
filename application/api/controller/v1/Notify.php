<?php

namespace app\api\controller\v1;

use app\api\model\GzhUser;
use app\api\model\UserExt;
use app\base\service\WxMsg;
use app\base\controller\Base;
use app\base\service\WxAPI;
use think\Log;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg(input('appid'));

        $wxMsg->checkSignature();
        $msg = $wxMsg->getMsg();
        // Log::record('收到客服消息：' . json_encode($msg, JSON_UNESCAPED_UNICODE), 'error');

        // {"ToUserName":"gh_7c87eaf27f5a",
        // "FromUserName":"oj77y5LIpHuIWUU2kW8BHVP4goPc","CreateTime":"1558089549",
        // "MsgType":"text","Content":"99","MsgId":"22306477788296821"}
        $content = $wxMsg->msgHandler($msg);
        $wxMsg->autoSend($msg, 'text', ['Content' => $content]);

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
                        },
                        {
                            "type": "view",
                            "name": "APP打榜",
                            "url": "https://m3w.cn/__uni__9bbb723"
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

        dump((new WxAPI('wx3507654fa8d00974'))->createMenu($data));
    }
}
