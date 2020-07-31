<?php


namespace app\api\model;


use app\base\service\Common;

class UserScrap extends \app\base\model\Base
{

    public static function add($user_id, $scrap_id, $number)
    {
        $map = compact ('user_id', 'scrap_id');
        $exist = self::where($map)->find ();
        if ($exist) {
            $number = bcadd ($number, $exist['number']);
            $updated = self::where('id', $exist['id'])->update(compact ('number'));
            return (bool) $updated;
        } else {
            $data = ['number' => $number];
            self::create (array_merge ($data, $map));

            return true;
        }
    }

    /**
     * 兑换
     *
     * @param $user_id
     * @param $scrap_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function exchange($user_id, $scrap_id)
    {
        $map = compact ('user_id', 'scrap_id');
        $exist = self::where($map)->find ();
        if (empty($exist)) {
            Common::res (['code'=> 1, 'msg' => '你还没有碎片哦']);
        }

        $scrap = CfgScrap::get ($scrap_id);
        if (empty($scrap)) {
            Common::res (['code' => 1, 'msg' => '奖品已下架']);
        }

        if ($scrap['status'] == CfgScrap::OFF) {
            Common::res (['code' => 1, 'msg' => '奖品已下架']);
        }

        $diff = bcsub ($exist['number'], $scrap['count']);
        if ($diff < 0) {
            Common::res (['code' => 1, 'msg' => '碎片数量不够哦']);
        }

        $update = [
            'number' => $diff,
            'exchange' => bcadd ($exist['exchange'], 1),
            'exchange_time' => time (),
        ];

        $updated = self::where('id', $exist['id'])->update($update);

        return (bool)$updated;
    }
}