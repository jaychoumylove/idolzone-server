<?php

namespace app\api\model;

use app\base\model\Base;

class LogUserContribute extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public static function getRank($starid, $time, $page, $size)
    {
        if ($starid) {
            $w = ['star_id' => $starid];
        } else {
            $w = '1=1';
        }

        return self::with('User')->where($w)->whereTime('create_time', $time)->group('user_id')
            ->field('user_id,star_id,sum(contribute) as contribute')->order('contribute desc')->select();
    }
}
