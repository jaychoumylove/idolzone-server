<?php


namespace app\api\model;


use app\base\model\Base;

class WelfareUser extends Base
{
    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }
}