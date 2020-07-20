<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class OpenRank extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public function UserInfo()
    {
        return $this->hasOne('User', 'id', 'user_id');
    }
    //
    public static function assist($open_id, $user_id, $flower)
    {
        $map = compact ('open_id', 'user_id');
        $exist = self::get ($map);
        $data = [
            'count' => $flower
        ];
        if (empty($exist)) {
            self::create (array_merge ($map, $data));
        } else {
            $data['count'] = bcadd ($exist['count'], $data['count']);
            self::where('id', $exist['id'])
                ->where ('count', $exist['count'])
                ->update($data);
        }
    }

    public static function getPager($map, $page = 1, $size = 10)
    {
        return self::with('user')
            ->where ($map)
            ->order ([
                'count' => 'desc',
                'id' => 'desc'
            ])
            ->page ($page, $size)
            ->select ();
    }
}
