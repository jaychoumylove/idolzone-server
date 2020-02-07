<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class FatherEarn extends Base
{
    public static function add($uid, $update)
    {
        $isExist = self::where('user_id', $uid)->find();
        if ($isExist) {
            self::where('user_id', $uid)->update([
                'coin' => Db::raw('coin+' . (isset($update['coin']) ? $update['coin'] : 0)),
                'flower' => Db::raw('flower+' . (isset($update['flower']) ? $update['flower'] : 0)),
                'stone' => Db::raw('stone+' . (isset($update['stone']) ? $update['stone'] : 0)),
                'trumpet' => Db::raw('trumpet+' . (isset($update['trumpet']) ? $update['trumpet'] : 0)),
            ]);
        } else {
            self::create([
                'user_id' => $uid,
                'coin' => isset($update['coin']) ? $update['coin'] : 0,
                'flower' => isset($update['flower']) ? $update['flower'] : 0,
                'stone' => isset($update['stone']) ? $update['stone'] : 0,
                'trumpet' =>  isset($update['trumpet']) ? $update['trumpet'] : 0,
            ]);
        }
    }
}
