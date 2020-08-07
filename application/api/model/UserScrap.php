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
            $updated = self::where('id', $exist['id'])->update(compact ('number'));
            return (bool) $updated;
        } else {
            $data = ['exchange' => 1];
            self::create (array_merge ($data, $map));

            return true;
        }
    }

    /**
     * 兑换
     *
     * @param $user_id
     * @param $scrap_id
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function exchange($user_id, $scrap_id)
    {
        $map = compact ('user_id', 'scrap_id');
        $exist = self::where($map)->find ();

        $scrapNum = UserExt::where('user_id', $user_id)->value ('scrap');
        if (empty($scrapNum)) {
            Common::res (['code'=> 1, 'msg' => '你还没有幸运碎片哦']);
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

        if ($scrap['count']) {
            $diff = bcsub ($scrapNum, $scrap['count']);
            if ($diff < 0) {
                Common::res (['code' => 1, 'msg' => '幸运碎片数量不够哦']);
            }
        }

        if ($scrap['key'] == CfgScrap::COIN) {
            $personLimit = $scrap['extra']['person'];
            if ($personLimit <= $exist['exchange']) {
                Common::res (['code' => 1, 'msg' => '兑换失败,请联系客服']);
            }

            if (Lock::getVal('week_end')['value'] == 1) {
                Common::res(['code' => 1, 'msg' => '金豆结算中，请稍后再试！']);
            }
        }

        Db::startTrans ();
        try {
            if ($exist) {
                $update = [
                    'exchange' => bcadd ($exist['exchange'], 1),
                    'exchange_time' => time (),
                ];
                $updated = self::where('id', $exist['id'])->update($update);
                if (empty($updated)) {
                    throw new Exception('更新失败');
                }
            } else {
                $data = ['exchange' => 1];
                self::create (array_merge ($data, $map));
            }

            $updated = CfgScrap::where('id', $scrap_id)->update([
                'exchange_number' => bcadd ($scrap['exchange_number'], 1)
            ]);

            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            $scrapItem = [
                'number' => -$scrap['count'],
                'key' => 'scrap',
                'name' => '幸运碎片',
                'type' => 'scrap',
            ];

            $rewardItem = [
                'number' => 1,
                'key' => $scrap_id,
                'name' => $scrap['name'],
                'type' => RecLuckyDrawLog::SCRAP_L,
            ];

            RecLuckyDrawLog::create ([
                'user_id' => $user_id,
                'lucky_draw' => 0,
                'item' => [$scrapItem ,$rewardItem],
                'type' => RecLuckyDrawLog::EXCHANGE
            ]);
            $updated = UserExt::where('user_id', $user_id)->update([
                'scrap' => bcsub ($scrapNum, $scrap['count'])
            ]);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            if ($scrap['key'] == CfgScrap::COIN) {
                (new \app\api\service\User())->change ($user_id, ['coin' => $scrap['extra']['number']], '使用幸运碎片兑换金豆');
            } else {

            }

//            throw new Exception('something was wrong');
            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '兑换失败，请稍后再试']);
        }

        return $scrap['key'];
    }
}