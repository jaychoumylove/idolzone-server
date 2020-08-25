<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class AnimalLottery extends Base
{
    const ONCE = 'once';
    const MULTIPLE = 'multiple';
    const MAX = 100;

    public static function lottery($type, $user_id)
    {
        $nums = UserExt::where('user_id', $user_id)->value('animal_lottery');
        if ($nums >= self::MAX) {
            Common::res(['code' => 1, 'msg' => '今日召唤数已用完']);
        }


    }
}