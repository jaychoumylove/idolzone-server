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
            $fanclubs= ActiveDragonBoatFestivalFanclub::where('active_id',$value['id'])->order('total_count desc,create_time asc')->limit(4)->select();
            $fanclubs = json_decode(json_encode($fanclubs),TRUE);
            $value['fanclub']= array_pad($fanclubs,4,[]);
        }

        return $list;
    }
}
