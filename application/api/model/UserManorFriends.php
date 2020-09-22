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

    public static function addFriend($user_id, $friend_id)
    {
        if ($user_id == $friend_id) {
            Common::res(['code' => 1, 'msg' => '不可以添加自己哦']);
        }
        $map    = compact('user_id', 'friend_id');
        $exist1 = self::get($map);
        if ($exist1) {
            Common::res(['code' => 1, 'msg' => '你们已经是好友了']);
        }
        $exist2 = self::get(array_reverse($map));
        if ($exist2) {
            Common::res(['code' => 1, 'msg' => '你们已经是好友了']);
        }

        $userStar = UserStar::getStarId($user_id);
        if (empty($userStar)) {
            Common::res(['code' => 1, 'msg' => '请先加入圈子']);
        }
        $friendStar = UserStar::getStarId($friend_id);
        if (empty($friendStar)) {
            Common::res(['code' => 1, 'msg' => '请让对方加入圈子']);
        }
        if ($userStar != $friendStar) {
            Common::res(['code' => 1, 'msg' => '你们不在同一个圈子哦']);
        }

        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        if ((int)$config['max_friend_num']) {
            $max = self::where('user_id', $user_id)
                ->whereOr('friend_id', $user_id)
                ->count();

            if ($max >= (int)$config['max_friend_num']) {
                Common::res(['code' => 1, 'msg' => '达到好友数量上限']);
            }
            $max = self::where('user_id', $friend_id)
                ->whereOr('friend_id', $friend_id)
                ->count();
            if ($max >= (int)$config['max_friend_num']) {
                Common::res(['code' => 1, 'msg' => '对方达到好友数量上限']);
            }
        }
        self::create($map);
    }

    public static function removeFriend($user_id, $friend_id)
    {
        if ($user_id == $friend_id) {
            return;
        }
        $map    = compact('user_id', 'friend_id');
        $exist1 = self::get($map);
        if (empty($exist1)) {
            $map = array_reverse($map);
            $exist2 = self::get($map);
            if (empty($exist2)) {
                return;
            }
        }

        self::where($map)->delete(true);
    }

}