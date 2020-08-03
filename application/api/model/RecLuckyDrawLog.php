<?php


namespace app\api\model;


class RecLuckyDrawLog extends \app\base\model\Base
{
    const SCRAP_L = 'SCRAP_L'; // 碎片成品

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function setItemAttr($value)
    {
        if (is_array ($value)) $value = json_encode ($value);

        return $value;
    }

    public function getItemAttr($value)
    {
        return json_decode ($value, true);
    }
}