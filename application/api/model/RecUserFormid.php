<?php

namespace app\api\model;

use app\base\model\Base;

class RecUserFormid extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }
}
