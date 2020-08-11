<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgHeadwear extends Base
{
    const FLOWER      = 'achievement_flower';
    const FLOWER_TIME = 'achievement_flower_time';
    const NEW_GUY     = 'achievement_new_guy';
    const PK          = 'achievement_pk';

    public static function getAll($uid)
    {
        $myHeadwearHas = HeadwearUser::where('uid', $uid)->where('status', 0)->column('hid');
        $myHeadwearUse = HeadwearUser::where('uid', $uid)->where('status', 1)->column('hid');
        $list = self::order('sort desc,diamond asc')->select();

        foreach ($list as $key => &$value) {
            $value['status'] = -1;
            if (in_array($value['id'], $myHeadwearHas)) {
                $value['status'] = 0;
            }
            if (in_array($value['id'], $myHeadwearUse)) {
                $value['status'] = 1;
                // 正在佩戴的头饰
                $res['cur'] = $value;
            }
        }
        $res['list'] = $list;
        return $res;
    }
}
