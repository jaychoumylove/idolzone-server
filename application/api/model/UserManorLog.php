<?php


namespace app\api\model;


use app\base\model\Base;

class UserManorLog extends Base
{
    // 升级
    // 召唤
    // 灵丹获取
    public function setDataAttr($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }

    public function getDataAttr($value)
    {
        return json_decode($value, true);
    }

    public static function record($user_id, $data, $content, $type)
    {
        self::create(compact('user_id', 'data', 'content', 'type'));
    }

    public static function recordTwelveLvUp($user_id, $animal, $lv, $number)
    {
        $type = 'LV_UP';
        $data = [
            'animal_id' => $animal['id'],
            'image' => $animal['scrap_img'],
            'number' => -abs($number),
        ];
        $content = sprintf('升级%s至Lv%s', $animal['name'], $lv);

        self::record($user_id, $data, $content, $type);
    }

    public static function recordSecret($user_id, $animal, $lv, $number, $unlock = false)
    {
        $data = [
            'animal_id' => $animal['id'],
            'image' => $animal['scrap_img'],
            'number' => -abs($number),
        ];
        if ($unlock) {
            $type = 'UNLOCK';
            $content = sprintf('解锁%s', $animal['name']);
        } else {
            $type = 'LV_UP';
            $content = sprintf('升级%s至Lv%s', $animal['name'], $lv);
        }

        self::record($user_id, $data, $content, $type);
    }

    public static function recordLottery($user_id, $animal, $number)
    {
        $type = 'ANIMAL_LOTTERY';
        $content = sprintf('获得%s', $animal['scrap_name']);
        $data = [
            'animal_id' => $animal['id'],
            'image' => $animal['scrap_name'],
            'number' => abs($number)
        ];

        self::record($user_id, $data, $content, $type);
    }

    public static function recordPanacea($user_id, $panacea, $content)
    {
        $type = $panacea > 0 ? 'INCOME_PANACEA':'SPEND_PANACEA';
        $data = [
            'panacea' => $panacea,
            'image' => '',
            'number' => $panacea > 0 ? abs($panacea): -abs($panacea)
        ];
        self::record($user_id, $data, $content, $type);
    }
}