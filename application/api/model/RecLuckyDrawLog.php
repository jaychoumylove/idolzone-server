<?php


namespace app\api\model;


class RecLuckyDrawLog extends \app\base\model\Base
{
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