<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class FanclubUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }
}
