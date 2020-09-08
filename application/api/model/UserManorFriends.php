<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class UserManorFriends extends Base
{
    public static function addFriend($user_id, $friend)
    {
        $map = compact('user_id', 'friend');
        $exist = self::get($map);
        if ($exist) {
            Common::res(['code' => 1, 'msg' => '你们已经是好友了']);
        }

        self::create($map);
    }
}