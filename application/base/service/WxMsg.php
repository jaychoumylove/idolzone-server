<?php

namespace app\base\service;

use app\api\model\GzhTemplate;
use app\api\model\GzhUser;
use app\api\model\User;
use app\api\model\UserExt;
use app\api\model\WxImg;
use think\Log;

class WxMsg
{
    private $appinfo;
    public function __construct($w = null)
    {
        $this->appinfo = Common::getAppinfo($w);
    }

    /**
     * 解密并返回收到的消息体
     * @return array 消息体
     */
    public function getMsg()
    {
        require_once APP_PATH . 'wx/crypto/wxBizMsgCrypt.php';
        $pc = new \WXBizMsgCrypt($this->appinfo['signature_token'], $this->appinfo['encoding_aes_key'], $this->appinfo['appid']);
        $xmlData = file_get_contents('php://input');
        $msg = '';
        $pc->decryptMsg(input('msg_signature'), input('timestamp'), input('nonce'), $xmlData, $msg);

        return Common::fromXml($msg);
    }

    /**
     * 验证消息signature
     */
    public function checkSignature()
    {
        $signature = input('signature');
        $timestamp = input('timestamp');
        $nonce = input('nonce');
        $echostr = input('echostr');

        $token = $this->appinfo['signature_token'];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            // 验证通过
            if ($echostr) die($echostr);
        } else {
            die();
        }
    }

    /**上传临时图片，保存mediaId */
    public function getMediaId($realPath)
    {
        $img = WxImg::where(['appid' => $this->appinfo['appid'], 'img_local_url' => $realPath])->find();

        if ($img) {
            if ($img['expire_in'] < time()) { // 已过期
                $res = (new WxAPI($this->appinfo['appid']))->uploadMedia($realPath);
                if (isset($res['media_id'])) {
                    WxImg::where(['appid' => $this->appinfo['appid'], 'img_local_url' => $realPath])->update([
                        'media_id' => $res['media_id'],
                        'expire_in' => time() + 3600 * 24 * 3
                    ]);
                    return $res['media_id'];
                }
            } else {
                return $img['media_id'];
            }
        } else {
            $res = (new WxAPI($this->appinfo['appid']))->uploadMedia($realPath);
            if (isset($res['media_id'])) {
                WxImg::create([
                    'appid' => $this->appinfo['appid'],
                    'img_local_url' => $realPath,
                    'media_id' => $res['media_id'],
                    'expire_in' => time() + 3600 * 24 * 3
                ]);
                return $res['media_id'];
            }
        }
    }

    /**
     * 自动(被动)回复
     * @param array $msgFrom 消息来源
     */
    public function autoSend($msgFrom, $msgType, $msgBody)
    {
        $content = [
            'ToUserName' => $msgFrom['FromUserName'],
            'FromUserName' => $msgFrom['ToUserName'],
            'CreateTime' => time(),
            'MsgType' => $msgType,
        ];

        $content = array_merge($content, $msgBody);
        die(Common::toXml($content));
    }

    /**
     * 处理受到的消息
     * 并获取需要回复的消息
     */
    public function msgHandler($msg)
    {
        // 用户id
        $user_id = $this->getUser($msg['FromUserName']);

        $content = '欢迎！';
        if ($msg['MsgType'] == 'text') {
            // 文本消息
            if ($msg['Content'] == '农场补偿' || $msg['Content'] == 2) {
                // 补偿
                if (!$user_id) {
                    $content = '未找到用户，可能是因为您还未进入应用游玩';
                } else {
                    $content = UserExt::redress($user_id);
                    $content .= '<a data-miniprogram-appid="wx3a69eb5e1b2a7fa9" data-miniprogram-path="/pages/user/log" href="http://mp.weixin.qq.com/s?__biz=Mzg3MjAwODQ0Mw==&mid=2247483657&idx=1&sn=f6fed458fdb14f16f8a0e035b874a462&chksm=cef49e9df983178b8fc49143703041fa2b1c230da13d4154b11403d1cd215f38892c16f3a0be#rd">点击此链接去查看</a>';
                }
            } else if ($msg['Content'] == '签到' || $msg['Content'] == 1) {
                // 每日签到
                if (!$user_id) {
                    $content = '未找到用户，可能是因为您还未进入应用游玩';
                } else {
                    $content = UserExt::gzhSignin($user_id);
                    // $content .= "\n\n" . UserProp::giveRechargeTicketEveryday($user_id);
                }
            }
        } else if ($msg['MsgType'] == 'event') {
            // 事件消息
            if ($msg['EventKey'] == 'CLICK_kefu') {
                $content = " 【联系客服】\n您的用户ID为：" . ($user_id * 1234 ? $user_id * 1234 : '') . "\n请加客服（大白）微信：vpanfxcom\n请一定注明反馈的问题或者建议，否则可能会被忽略哦！";
            } else if ($msg['EventKey'] == 'CLICK_lianxi') {
                $content = " 【商务合作】\n寻求合作及赞助可发送邮件：alben.liu@qq.com\n请一定注明公司、姓名、以及合作内容、品牌，否则可能会被忽略哦！";
            } else if ($msg['Event'] == 'subscribe') {
                // 关注
                $content = '谢谢你那么可爱还关注了我~';
            } else if ($msg['Event'] == 'subscribe') {
                // if ($user_id) {
                //     // 匹配到用户

                //     GzhTemplate::getTemplate(1, $msg['FromUserName'], 'wx3a69eb5e1b2a7fa9', 'https://idolzone.cyoor.com', [
                //         '账号绑定成功', 
                // User::where('id', $user_id)->value('nickname'),
                //     ]);
                // }
                // $data = '{"touser":"oVR6g0keVmckJh257vfbtCMxYj0M","template_id":"4JUFNAzJFEbNJo5dOgEwI3bKSeOgS04q33J3110lz08","url":"https://idolzone.cyoor.com/","miniprogram":{"appid":"wx3a69eb5e1b2a7fa9"},"data":{"first":{"value":"你已绑定账号成功"},"keyword1":{"value":"巧克力"},"keyword2":{"value":"绑定成功,小程序订阅功能已开启"},"remark":{"value":">>>点击即可回到小程序订阅数据推送"}}}';
                // $wxApi = new WxAPI($this->appinfo['appid']);
                // $wxApi->sendMessageGzh($data);
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

        // 关注/取关
        $subscribe = (int) !($msg['MsgType'] == 'event' && $msg['Event'] == 'unsubscribe');
        GzhUser::gzhSubscribe(input('appid'), $user_id, $msg['FromUserName'], $subscribe);

        return $content;
    }

    /**
     * 公众号通过unionid获取唯一的用户id,需要关注公众号
     * @param string $openid FromUserName
     */
    public function getUser($openid)
    {
        $wxApi = new WxAPI($this->appinfo['appid']);
        $res = $wxApi->getUserInfocgi($openid);

        if (isset($res['unionid'])) {
            return User::where(['unionid' => $res['unionid']])->value('id');
        }
    }

    /**下载图片 */
    public function download($url)
    {
        $content = file_get_contents($url);
        $filePath = ROOT_PATH . 'public/uploads/' . time() . mt_rand(10000, 99999);
        file_put_contents($filePath, $content);
        return $filePath;
    }
}
