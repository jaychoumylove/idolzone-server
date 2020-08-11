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

    const NORMAL_TYPE = 'NORMAL';
    const ACHIEVEMENT_TYPE = 'ACHIEVEMENT';

    public static function getAll($uid)
    {
        $myHeadwearHas = HeadwearUser::where('uid', $uid)->where('status', 0)->column('hid');
        $myHeadwearUse = HeadwearUser::where('uid', $uid)->where('status', 1)->column('hid');
        $list = self::order('sort desc,diamond asc')->select();

        $newList = [
            [],
            []
        ];
        $config = [
            [
                'value' =>  self::NORMAL_TYPE,
                'label' => "往期头饰",
            ],
            [
                'value' => self::ACHIEVEMENT_TYPE,
                'label' => "成就头饰",
            ],
        ];
        foreach ($list as $key => $value) {
            $value['status'] = -1;
            if (in_array($value['id'], $myHeadwearHas)) {
                $value['status'] = 0;
            }
            if (in_array($value['id'], $myHeadwearUse)) {
                $value['status'] = 1;
                // 正在佩戴的头饰
                $res['cur'] = $value;
            }
            $index = 0;
            if ($value['type'] == self::NORMAL_TYPE) {
                $index = 0;
            }
            if ($value['type'] == self::ACHIEVEMENT_TYPE) {
                $index = 1;
            }
            array_push ($newList[$index], $value);
        }
        $res['list'] = $newList;
        $res['config'] = $config;
        return $res;
    }
}
