<?php


namespace app\api\model;


use app\base\service\Common;
use think\Db;
use think\Exception;

class CfgLuckyDraw extends \app\base\model\Base
{
    const CURRENCY = 'currency';
    const SCRAP = 'scrap';

    public function getRewardAttr($value)
    {
        return json_decode ($value, true);
    }

    public static function getLuckyDraw()
    {
        return self::order (['create_time' => 'desc'])->find ();
    }

    public static function start($user_id)
    {
        $luckyDrawTrick = Prop::where('key', Prop::LUCKY_DRAW)->value ('id');

        $prop = UserProp::getOneProp ($user_id, $luckyDrawTrick);
        if (is_object ($prop)) $prop = $prop->toArray ();
        if (empty($prop)) {
            Common::res (['code' => 1, 'msg' => '抽奖券已用完']);
        }

        $luckyDraw = self::getLuckyDraw ();
        if (is_object ($luckyDraw)) $luckyDraw = $luckyDraw->toArray ();
        if (empty($luckyDraw)) {
            Common::res (['code' => 1, 'msg' => '暂未开放']);
        }

        $chooseItem = Common::lottery ($luckyDraw['reward'], 'weights');

//        $scrapItem = array_filter ($luckyDraw['reward'], function ($item) {
//            return  $item['type'] == self::SCRAP;
//        });
//
//        $scrapItem = array_values ($scrapItem);
//        $chooseItem = $scrapItem[0];
        Db::startTrans ();
        try {
            // 消耗抽奖券
            $updated = UserProp::where('id', $prop['id'])->update([
                'status' => 1,
                'use_time' => time ()
            ]);
            if (empty($updated)) {
                Common::res (['code' => 1, 'msg' => '请稍后再试']);
            }

            // 发放奖励
            if ($chooseItem['type'] == self::CURRENCY) {
                $currencyMap = ['stone', 'coin', 'flower', 'old_coin', 'trumpet'];
                $data = [];
                if (in_array ($chooseItem['key'], $currencyMap)) {
                    $data[$chooseItem['key']] = $chooseItem['number'];
                }

                (new \app\api\service\User())->change ($user_id, $data, '幸运抽奖');
            }

            // 发放奖励
            if ($chooseItem['type'] == self::SCRAP) {
                $added = UserScrap::add($user_id, $chooseItem['key'], $chooseItem['number']);
                if (empty($added)) Common::res (['code' => 1, 'msg' => '请稍后再试']);
            }

            // 记录抽奖信息
            $log = [
                'user_id' => $user_id,
                'lucky_draw' => $luckyDraw['id'],
                'item' => $chooseItem,
            ];
            RecLuckyDrawLog::create ($log);

//            throw new Exception('something was wrong');

            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }

        return $chooseItem;
    }
}