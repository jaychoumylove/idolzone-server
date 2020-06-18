<?php

namespace app\api\model;

use app\base\model\Base;

class ActiveDragonBoatFestival extends Base
{
    public static function getList()
    {
        $list = self::where('1=1')->order('bonus','desc')->select();
        $list = json_decode(json_encode($list),TRUE);
        foreach ($list as &$value){
            $value['fanclub']= ActiveDragonBoatFestivalFanclub::where('active_id',$value['id'])->order('total_count','desc')->limit(4)->select();
        }

        return $list;
    }
}
