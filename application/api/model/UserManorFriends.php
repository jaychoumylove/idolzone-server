<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class UserManorFriends extends Base
{
    public function friend()
    {
        return $this->hasOne('User', 'id', 'friend_id')->field('id,avatarurl,nickname');
    }

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public static function addFriend($user_id, $friend)
    {
        if ($user_id == $friend) {
            Common::res(['code' => 1, 'msg' => '不可以添加自己哦']);
        }
        $map    = compact('user_id', 'friend');
        $exist1 = self::get($map);
        if ($exist1) {
            Common::res(['code' => 1, 'msg' => '你们已经是好友了']);
        }
        $exist2 = self::get(array_reverse($map));
        if ($exist2) {
            Common::res(['code' => 1, 'msg' => '你们已经是好友了']);
        }
        self::create($map);
    }
}