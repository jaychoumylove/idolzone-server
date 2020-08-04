<?php


namespace app\api\model;


class CfgScrap extends \app\base\model\Base
{
    const ON = 'ON';
    const OFF = 'OFF';
    const DAY = 'DAY';
    const WEEK = 'WEEK';
    const COIN = 'coin';

    public function getExtraAttr($value)
    {
        return json_decode ($value, true);
    }
}