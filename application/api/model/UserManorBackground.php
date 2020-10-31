<?php


namespace app\api\model;


use app\base\model\Base;

class UserManorBackground extends Base
{
    public static function getMaxHours($default, $user_id)
    {
        // 获取存储时长 单位：小时
        $addHoursBg = CfgManorBackground::where('add_hours', '>', 0)
            ->field('id,add_hours')
            ->select();
        if (is_object($addHoursBg)) $addHoursBg = $addHoursBg->toArray();

        if (empty($addHoursBg)) return $default;
        $hoursDict = array_column($addHoursBg, 'add_hours', 'id');
        $bgIds = array_keys($hoursDict);

        $hasBg = self::where('user_id', $user_id)
            ->where('background', 'in', $bgIds)
            ->column('background');

        if (empty($hasBg)) return $default;

        $hours = $default;
        foreach ($hasBg as $item) {
            $hours += (int)$hoursDict[$item];
        }

        return $hours;
    }
}