<?php

namespace app\api\model;

use app\base\model\Base;

class Cfg extends Base
{
    public static function getCfg($key)
    {
        $value = self::where(['key' => $key])->value('value');

        $res = json_decode($value, true);

        if ($res) return $res;
        else return $value;
    }

    public static function getList()
    {
        $list = self::all(['show' => 1]);
        $res = [];
        foreach ($list as $value) {
            $val = json_decode($value['value'], true);
            if (!$val) $val = $value['value'];

            $res[$value['key']] = $val;
        }

        return $res;
    }
    
    public static function isPkactiveStart(){
        $pkactiveDate = self::getCfg('pkactive_date');        
        return  (time() > strtotime($pkactiveDate[0]) && time() < strtotime($pkactiveDate[1]));
    }

    public static function is618activeStart(){
        $btnCfgDate = self::getCfg('btn_cfg');
        $result = false;
        if(count($btnCfgDate['group'])>0){
            foreach ($btnCfgDate['group'] as $value){
                if($value['path']=='/pages/active/active618'){
                    if(time() > strtotime($value['start_time']) && time() < strtotime($value['end_time']) && $value['status']!=0){
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }

    /*活动起止时间
     * cfg表中的配置
     * 类似 biaobai_date	["2020-05-21 00:00:00","2020-05-21 00:00:00"]
     * */
    public static function getStatus($key){
        $res = self::getCfg($key);
        return  (time() > strtotime($res[0]) && time() < strtotime($res[1]));
    }
}
