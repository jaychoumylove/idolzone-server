<?php


namespace app\api\model;


class RecUserPaidLog extends \app\base\model\Base
{
    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field ('id,nickname,avatarurl');
    }

    public function setItemAttr($value)
    {
        return json_encode ($value);
    }

    public function getItemAttr($value)
    {
        return json_decode ($value, true);
    }
}