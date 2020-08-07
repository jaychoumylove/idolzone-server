<?php

namespace app\api\model;

use app\base\model\Base;

class Cfg extends Base
{
    const WEAL_ACTIVE_PATH = '/pages/active/weal';
    const ACTIVE_CONFORM   = 'active_conform';
    const RECHARGE_LUCKY   = 'recharge_lucky';

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

    /**
     * @param array $config
     * @return bool
     */
    public static function checkMultipleDrawAble(array $config)
    {
        if (empty($config)) return false;

        $now         = time ();

        if ($now < strtotime ($config['draw_time']['start'])) {
            return false;
        }
        if ($now > strtotime ($config['draw_time']['end'])) {
            return false;
        }

        // 没有限制时间区间
//        $currentDate = [
//            'fullTime' => $now,
//            'date'     => date ('Y-m-d H:i:s'),
//            'hours'    => (int)date ('H', $now),
//            'minutes'  => (int)date ('i', $now),
//            'seconds'  => (int)date ('s', $now),
//        ];
//        $limitStart = explode (':', $config['limit_time']['start']);
//        $limitEnd = explode (':', $config['limit_time']['end']);
//        if ($currentDate['hours'] < (int)$limitStart[0]) {
//            return false;
//        }
//        if ($currentDate['minutes'] < (int)$limitStart[1]) {
//            return false;
//        }
//        if ($currentDate['seconds'] < (int)$limitStart[2]) {
//            return false;
//        }
//        if ($currentDate['hours'] > (int)$limitEnd[0]) {
//            return false;
//        }
//        if ($currentDate['minutes'] > (int)$limitEnd[1]) {
//            return false;
//        }
//        if ($currentDate['seconds'] > (int)$limitEnd[2]) {
//            return false;
//        }

        return true;
    }
}
