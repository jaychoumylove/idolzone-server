<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class RecLotteryBox extends Base
{
    //

    public function user()
    {
        return $this->belongsTo('User', 'user_id')->field('id,nickname,avatarurl');
    }
}
