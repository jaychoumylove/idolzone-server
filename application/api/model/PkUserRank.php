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

    public function star()
    {
        return $this->hasOne('Star', 'mid', 'id');
    }

    public static function settleHot($pk_time)
    {
        $top = self::where(['last_pk_time' => $pk_time])
            ->order ([
                'last_pk_count' => 'desc',
                'update_time' => 'asc'
            ])
            ->limit (3)
            ->select ();
        if (is_object ($top)) $top = $top->toArray ();

        foreach ($top as $item) {
            UserAchievementHeal::recordTime ($item['uid'],
                $item['star_id'],
                UserAchievementHeal::TIMER,
                UserAchievementHeal::PK);
        }
    }
}