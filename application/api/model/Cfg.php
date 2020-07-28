<?php

namespace app\api\model;

use app\base\model\Base;

class Cfg extends Base
{
    const WEAL_ACTIVE_PATH = '/pages/active/weal';
    const ACTIVE_CONFORM   = 'active_conform';

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

    /**
     * @return bool
     */
    public static function checkWealActive()
    {
        $btnCfgDate = self::getCfg('btn_cfg');

        foreach ($btnCfgDate['group'] as $value){
            if($value['path']=='/pages/active/weal'){
                if(time() > strtotime($value['start_time']) && time() < strtotime($value['end_time']) && $value['status']!=0){
                    $result = true;
                }
            }
        }

        return isset($result) ? $result: false;
    }

    public static function isActiveDragonBoatFestivalStart(){
        $btnCfgDate = self::getCfg('btn_cfg');
        $result = false;
        if(count($btnCfgDate['group'])>0){
            foreach ($btnCfgDate['group'] as $value){
                if($value['path']=='/pages/active/dragon_boat_festival'){
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

    /**
     * @param $path
     * @return bool
     */
    public static function checkActiveByPathInBtnGroup($path)
    {
        $btnCfgDate = self::getCfg('btn_cfg');

        $group = $btnCfgDate['group'];

        $checkItem = array_filter ($group, function ($item) use ($path) {
            return $item['path'] == $path;
        });

        if (empty($checkItem)) return false;

        $check = array_shift ($checkItem);

        // timer
        $currentTime = time ();

        $notBefore = $currentTime > strtotime ($check['start_time']);
        if (empty($notBefore)) return false;

        $notAfter = $currentTime < strtotime ($check['end_time']);
        if (empty($notAfter)) return false;

        return (bool)$check['status'];
    }
}
