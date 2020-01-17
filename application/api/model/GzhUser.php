<?php

namespace app\api\model;

use app\base\model\Base;

class GzhUser extends Base
{
    /**公众号关注 */
    public static function gzhSubscribe($appid, $uid, $openid, $sub = 1)
    {
        $isExist = self::where('openid', $openid)->find();
        if (!$isExist) {
            self::create([
                'user_id' => $uid,
                'gzh_appid' => $appid,
                'openid' => $openid,
                'subscribe' => $sub,
            ]);
        } else {
            self::where('openid', $openid)->update([
                'user_id' => $uid,
                'gzh_appid' => $appid,
                'subscribe' => $sub,
            ]);
        }
    }
}
