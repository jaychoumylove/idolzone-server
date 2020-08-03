<?php


namespace app\api\model;


use app\base\service\Common;
use think\Db;
use think\Exception;

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

        if ((int)$scrap['limit_exchange']) {
            if ((int)$scrap['limit_exchange'] <= (int)$scrap['exchange_number']) {
                Common::res (['code' => 1, 'msg' => "奖品已被兑换完了"]);
            }
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

        Db::startTrans ();
        try {
            $updated = self::where('id', $exist['id'])->update($update);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            $updated = CfgScrap::where('id', $scrap_id)->update([
                'exchange_number' => bcadd ($scrap['exchange_number'], 1)
            ]);

            if (empty($updated)) {
                throw new Exception('更新失败');
            }
            $item = [
                'name' => "",
                'number' => 0,
                'key' => 0,
                'type' => CfgLuckyDraw::SCRAP
            ];

            $scrapItem = [
                'name' => $scrap['name'] . '碎片',
                'number' => -$scrap['count'],
                'key' => $scrap_id
            ];
            RecLuckyDrawLog::create ([
                'user_id' => $user_id,
                'lucky_draw' => 0,
                'item' => array_merge ($item, $scrapItem),
            ]);

            $wholeItem = [
                'name' => $scrap['name'],
                'number' => 1,
                'key' => $scrap_id,
                'type' => RecLuckyDrawLog::SCRAP_L
            ];
            RecLuckyDrawLog::create ([
                'user_id' => $user_id,
                'lucky_draw' => 0,
                'item' => array_merge ($item, $wholeItem),
            ]);

            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
            Common::res (['code' => 1, 'msg' => '兑换失败，请稍后再试']);
        }
    }
}