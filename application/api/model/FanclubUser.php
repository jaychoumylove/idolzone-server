<?php

namespace app\api\model;

use app\api\controller\v1\FansClub;
use app\base\model\Base;
use think\Model;

class FanclubUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function fanclub()
    {
        return $this->belongsTo('Fanclub', 'fanclub_id', 'id')->field('id,user_id,clubname,avatar');
    }

    /**是否是团长 */
    public static function isLeader($uid)
    {
        $fid = self::where('user_id', $uid)->value('fanclub_id');
        $f_user = Fanclub::where('id', $fid)->value('user_id');
        return $f_user == $uid;
    }
}
