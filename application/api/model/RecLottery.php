<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class RecLottery extends Base
{
    //

    public function user()
    {
        return $this->belongsTo('User', 'user_id')->field('id,nickname,avatarurl');
    }

    public function lottery()
    {
        return $this->belongsTo('CfgLottery', 'lottery_id', 'id');
    }
}
