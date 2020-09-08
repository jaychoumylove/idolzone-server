<?php


namespace app\api\model;


use app\base\model\Base;

class RecUserBackgroundTask extends Base
{
    const SUM        = 'SUM';
    const FLOWER_SUM = 'FLOWER_SUM';
    const ACTIVE     = 'ACTIVE';

    public static function record($user_id, $number, $type = self::FLOWER_SUM)
    {
        $map = compact('user_id', 'type');
        $exist = self::get($map);
        if ($exist) {
            $data = [
                'count_num' => bcadd($exist['count_num'], $number),
                'sum' => bcadd($exist['sum'], $number)
            ];

            self::where('id', $exist['id'])->update($data);
        } else {
            $data = [
                'count_num' => $number,
                'sum' => $number,
            ];
            self::create(array_merge($data, $map));
        }
    }
}