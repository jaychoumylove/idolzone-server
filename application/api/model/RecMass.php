<?php

namespace app\api\model;

use app\base\model\Base;

class RecMass extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'be_mass_uid')->field('id,nickname,avatarurl');
    }
}
