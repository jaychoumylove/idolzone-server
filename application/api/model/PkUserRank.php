<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class PkUserRank extends Base
{

    public function User()
    {
        return $this->belongsTo('User', 'uid', 'id')->field('id,nickname,avatarurl');
    }

    public static function settleHot($pk_time)
    {
        $map = compact ('pk_time');
        $top = self::where($map)
            ->order ([
                'send_hot' => 'desc',
                'id' => 'asc'
            ])
            ->limit (3)
            ->select ();
        if (is_object ($top)) $top = $top->toArray ();

        foreach ($top as $item) {
            UserAchievementHeal::recordTime ($item['uid'],
                $item['star_id'],
                bcmul (UserAchievementHeal::TIMER, 3),
                UserAchievementHeal::PK);
        }
    }
}