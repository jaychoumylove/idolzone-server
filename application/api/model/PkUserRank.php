<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class PkUserRank extends Base
{

    public function User()
    {
        return $this->belongsTo('User', 'uid', 'id')->field('id,nickname,avatarurl');
    }
}