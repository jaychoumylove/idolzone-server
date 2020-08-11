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
        $myHeadwear = HeadwearUser::where('uid', $uid)->select();
        if (is_object ($myHeadwear)) $myHeadwear = $myHeadwear->toArray ();

        $myHeadwearHas = array_filter ($myHeadwear, function ($item) {
            return $item['status'] == 0;
        });
        $myHeadwearUse = array_filter ($myHeadwear, function ($item) {
            return $item['status'] == 1;
        });

        $hasIds = array_column ($myHeadwearHas, 'hid');
        $useDict = array_column ($myHeadwearUse, null, 'hid');

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
            if (in_array($value['id'], $hasIds)) {
                $value['status'] = 0;
            }
            if (array_key_exists($value['id'], $useDict)) {
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
                if ($value['status'] == 1) {
                    $value['end_time'] = date('m月d日H时i分', strtotime ($useDict[$value['id']]['end_time']));
                }
            }
            array_push ($newList[$index], $value);
        }
        $res['list'] = $newList;
        $res['config'] = $config;
        return $res;
    }
}
