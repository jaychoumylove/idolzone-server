<?php

namespace app\api\model;

use app\base\model\Base;

class UserShareBox extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**
     * 增加宝箱
     */
    public static function addBox($user_id, $count, $num, $day=1,$people = 10)
    {
        $insert = [];
        for ($i = 0; $i < $num; $i++) {
            $insert[] = [
                'user_id' => $user_id,
                'coin' => $count,
                'people' => $people,
                'remaining_people' => $people,
                'end' => time()+$day*24*3600,
            ];
        }

        self::insertAll($insert);
    }


}
