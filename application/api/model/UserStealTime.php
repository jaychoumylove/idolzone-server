<?php

namespace app\api\model;

use app\base\model\Base;

class UserStealTime extends Base
{
    public static function setTime($uid, $index)
    {
        $item = self::get(['user_id' => $uid]);

        $leftTime = json_decode($item['left_time'], true);
        $leftTime[$index] = time();
        $leftTime = json_encode($leftTime);

        self::where(['user_id' => $uid])->update([
            'left_time' => $leftTime
        ]);
    }
}
