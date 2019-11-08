<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgPkTime extends Base
{
    /**团战状态 */
    public static function status()
    {
        // 全部的团战时间段
        $pkTime = self::all();
        $data['pkTimeList'] = $pkTime;

        foreach ($pkTime as $value) {
            $startTime = strtotime($value['start_time'] . ':00');
            $endTime = strtotime($value['end_time'] . ':00');

            if (time() >= $startTime && time() <= $endTime) {
                // 正在团战时间段
                $data['status'] = 2;
                // 时间点信息
                $data['curPkTime'] = $value;
                // 本场剩余时间
                $data['timeLeft'] = $endTime - time();

                break;
            } else if (time() < $startTime) {
                // 正在报名时间段
                $data['status'] = 1;
                $data['curPkTime'] = $value;
                // 距离开始还剩
                $data['timeLeft'] = $startTime - time();

                break;
            }
        }
        // 完整的pk时间
        $data['whole_time'] = date('Y-m-d', time()) . ' ' . $data['curPkTime']['start_time'] . ':00';

        return $data;
    }

    public static function getRelativeTime($pkId)
    {
        $pkStatus = self::status();

        if ($pkStatus['curPkTime']['id'] == $pkId) {
            // 当前场次
            $time = $pkStatus['whole_time'];
        } else {
            foreach ($pkStatus['pkTimeList'] as $value) {
                if ($pkId == $value['id']) {
                    $endTime = strtotime($value['end_time'] . ':00');

                    if (time() > $endTime) {
                        $time = date('Y-m-d', time()) . ' ' . $value['start_time'] . ':00';
                    } else {
                        $time = date('Y-m-d', time() - 3600 * 24) . ' ' . $value['start_time'] . ':00';
                    }
                }
            }
        }

        return $time;
    }
}
