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
                }
            } 
        }

        $wxMsg->autoSend($msg, 'text', [
            'Content' => $content
        ]);

        die('success');
    }
}
