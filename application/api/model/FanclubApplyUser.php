<?php

namespace app\api\model;

use app\base\model\Base;

class FanclubApplyUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }
}
