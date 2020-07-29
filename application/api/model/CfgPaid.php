<?php


namespace app\api\model;


class CfgPaid extends \app\base\model\Base
{
    const SUM = 'SUM';
    const DAY = 'DAY';

    const ON = 'ON';
    const OFF = 'OFF';

    public function getRewardAttr($value)
    {
        return json_decode ($value, true);
    }

    public function setRewardAttr($value)
    {
        return json_encode ($value);
    }

    public static function getCheckType($type)
    {
        $types = self::where('status', self::ON)->column ('type');
        return in_array ($type, $types);
    }
}