<?php


namespace app\api\model;


use app\base\model\Base;

class CfgAnimalLevel extends Base
{
    public function getImageAttr($value)
    {
        return $value ? json_decode($value, true): $value;
    }
}