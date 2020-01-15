<?php

namespace app\api\controller\v1;

use app\api\model\UserExt;
use app\api\model\UserProp;
use app\base\service\WxMsg;
use app\base\controller\Base;
use app\base\service\WxAPI;

class Notify extends Base
{

    public function receive()
    {
        $wxMsg = new WxMsg(input('appid'));

        $wxMsg->checkSignature();
        $msg = $wxMsg->getMsg();
        // Log::record('收到客服消息：' . json_encode($msg, JSON_UNESCAPED_UNICODE), 'error');

        $user_id = $wxMsg->getUser($msg['FromUserName']);

        // {"ToUserName":"gh_7c87eaf27f5a",
        // "FromUserName":"oj77y5LIpHuIWUU2kW8BHVP4goPc","CreateTime":"1558089549",
        // "MsgType":"text","Content":"99","MsgId":"22306477788296821"}
        $content = '欢迎~';
        if ($msg['MsgType'] == 'text') {
            if ($msg['Content'] == '农场补偿' || $msg['Content'] == 2 || $msg['Content'] == '2019') {
                // 补偿
                if (gettype($user_id) == 'integer') {
                    $content = UserExt::redress($user_id);
                    $content .= '<a data-miniprogram-appid="wx3a69eb5e1b2a7fa9" data-miniprogram-path="/pages/user/log" href="http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=2247483657&idx=1&sn=f6fed458fdb14f16f8a0e035b874a462&chksm=cef49e9df983178b8fc49143703041fa2b1c230da13d4154b11403d1cd215f38892c16f3a0be#rd">点击此链接去查看</a>';
                } else {
                    $content = '未找到用户，可能是因为您还未进入应用游玩';
                }
            } else if ($msg['Content'] == '签到' || $msg['Content'] == 1) {
                // 每日签到
                if (gettype($user_id) == 'integer') {
                    $content = UserExt::gzhSignin($user_id);
                    // $content .= "\n\n" . UserProp::giveRechargeTicketEveryday($user_id);
                } else {
                    $content = '未找到用户，可能是因为您还未进入应用游玩';
                }
            }
        } else if ($msg['MsgType'] == 'event') {
            if ($msg['EventKey'] == 'CLICK_kefu') {
                $content = " 【联系客服】\n您的用户ID为：" . ($user_id * 1234 ? $user_id * 1234 : '') . "\n请加客服（大白）微信：vpanfxcom\n请一定注明反馈的问题或者建议，否则可能会被忽略哦！";
            } else if ($msg['EventKey'] == 'CLICK_lianxi') {
                $content = " 【商务合作】\n寻求合作及赞助可发送邮件：alben.liu@qq.com\n请一定注明公司、姓名、以及合作内容、品牌，否则可能会被忽略哦！";
            } else if ($msg['Event'] == 'subscribe') {
                $content = '谢谢你那么可爱还关注了我~';
            }
        }

        $content .= "\n";
        $content .= "\n你可能对以下内容感兴趣：";
        // $content .= "\n回复“2019”领取年终福利";
        $content .= "\n回复“签到”领取每日签到奖励";
        $content .= "\n<a href='https://idolzone.cyoor.com/#/pages/charge/charge'>鲜花充值</a>";
        $content .= "\n<a href='http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=100000649&idx=1&sn=38d263825275b1051d539344692e15b7&chksm=4ef49c1d7983150b7b486381a147b972bf9c674d5683cc76d9455c718eb468c184453f7dbbde#rd'>榜单福利</a>";
        $content .= "\n<a href='http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=100000654&idx=1&sn=ec592eb867cc5c0f2aa40f9e3f4a6cc4&chksm=4ef49c1a7983150c37bd65374e1c7d63b26957d9fda988536b749aff92894e309501a0289d77#rd'>打榜攻略</a>";
        $content .= "\n<a href='http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=100000658&idx=1&sn=b064e45ac6b95604988f22dac343de58&chksm=4ef49c0679831510e51750ae994b8ff5b5e5052978fcd2a0449f8b9e9557fa24011fb9cc22da#rd'>充值优惠</a>";

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
