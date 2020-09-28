<?php


namespace app\api\model;


use app\base\model\Base;

class RecActiveYingyuan extends Base
{
    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function setItemAttr($value)
    {
        if (is_array ($value)) $value = json_encode ($value);

        return $value;
    }

    public function getItemAttr($value)
    {
        return json_decode ($value, true);
    }

    public static function getLogPager($user_id, $page, $size)
    {
        $count = self::where('user_id', $user_id)->count ();

        $list = self::where('user_id', $user_id)
            ->order ([
                'create_time' => "desc",
                'id' => 'desc'
            ])
            ->page ($page, $size)
            ->select ();
        if (is_object ($list)) $list = $list->toArray ();

        $data['list'] = $list;
        $data['count'] = $count;

        return $data;
    }
}