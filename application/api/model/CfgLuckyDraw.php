<?php


namespace app\api\model;


use app\base\service\Common;
use think\Db;
use think\Exception;

class CfgLuckyDraw extends \app\base\model\Base
{
    const CURRENCY = 'currency';
    const SCRAP = 'scrap';

    public static function startFifty($user_id)
    {
        if (Lock::getVal('week_end')['value'] == 1) {
            Common::res(['code' => 1, 'msg' => '金豆结算中，请稍后再试！']);
        }

        $prop_id = Prop::where('key', Prop::LUCKY_DRAW)->value ('id');

        $propsMap = compact ('user_id', 'prop_id');
        $propNum = (new UserProp())->readMaster ()
            ->where($propsMap)
            ->where('status', 0)
            ->count ();

        $config = Cfg::getCfg (Cfg::RECHARGE_LUCKY);

        $able = Cfg::checkMultipleDrawAble ($config['multiple_draw']);
        if (empty($able)) {
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }
        $max = $config['multiple_draw']['multiple'];

        if ($propNum < $max) {
            Common::res (['code' => 1, 'msg' => '抽奖券不足']);
        }

        $luckyDraw = self::getLuckyDraw ();
        if (is_object ($luckyDraw)) $luckyDraw = $luckyDraw->toArray ();
        if (empty($luckyDraw)) {
            Common::res (['code' => 1, 'msg' => '暂未开放']);
        }

        $array = range (0, bcsub ($max, 1));
        $choose = [];
        foreach ($array as $item) {
            $chooseItem = Common::lottery ($luckyDraw['reward'], 'weights');
            array_push ($choose, $chooseItem);
        }
        // 50连抽必中碎片
        $scrapItems = array_filter ($luckyDraw['reward'], function ($item) {
            return $item['type'] == CfgLuckyDraw::SCRAP;
        });
        $scrapItems = array_values ($scrapItems);
        $scrapItem = $scrapItems[0];

        $chooseItem = [];
        foreach ($luckyDraw['reward'] as $key => $value) {
            $chooses = array_filter ($choose, function ($item) use($value) {
                return $item == $value;
            });

            if ($chooses) {
                array_push ($chooseItem, array_merge ($value, ['times' => count ($chooses)]));
            }
        }

        Db::startTrans ();
        try {
            // 消耗抽奖券
            $updated = (new UserProp)->where($propsMap)
                ->where('status', 0)
                ->limit ($max)
                ->order ([
                    'create_time' => 'asc',
                    'id' => 'asc'
                ])
                ->update([
                    'status' => 1,
                    'use_time' => time ()
                ]);

            if (empty($updated) || $updated < $max) {
                Common::res (['code' => 1, 'msg' => '请稍后再试']);
            }

            // 发放奖励
            $currencyMap = ['stone', 'coin', 'flower', 'old_coin', 'trumpet'];
            $earn = [];
            $scraps = [$scrapItem['number']];
            foreach ($choose as $item) {
                if ($item['type'] == self::CURRENCY) {
                    if (in_array ($item['key'], $currencyMap)) {
                        if (array_key_exists ($item['key'], $earn)) {
                            $earn[$item['key']] = bcadd ($earn[$item['key']], $item['number']);
                        } else {
                            $earn[$item['key']] = $item['number'];
                        }
                    }
                }

                if ($item['type'] == self::SCRAP) {
                    array_push ($scraps, $item['number']);
                }
            }
            if ($earn) {
                (new \app\api\service\User())->change ($user_id, $earn, '使用抽奖券抽奖');
            }

            // 发放奖励
            if ($scraps) {
                $userScrap = (new UserExt)->readMaster ()->where('user_id', $user_id)->find ();
                $userScrapUpdate = ['scrap' => bcadd ($userScrap['scrap'], array_sum ($scraps))];
                if ($userScrap['scrap_time']) {
                    $userScrapUpdate['scrap_time'] = null;
                }
                $added = UserExt::where('id', $userScrap['id'])->update($userScrapUpdate);
                if (empty($added)) Common::res (['code' => 1, 'msg' => '请稍后再试']);
            }

            // 记录抽奖信息
            $insertItems = ['scrap' => $scrapItem];
            $log = [
                'user_id' => $user_id,
                'lucky_draw' => $luckyDraw['id'],
                'type' => RecLuckyDrawLog::MULTIPLE
            ];
            foreach ($chooseItem as $item) {
                $number = bcmul ($item['number'], $item['times']);
                if (array_key_exists ($item['key'], $insertItems)) {
                    $insertItems[$item['key']]['number'] = bcadd ($number, $insertItems[$item['key']]['number']);
                } else {
                    $item['number'] = $number;
                    unset($item['times']);
                    $insertItems[$item['key']] = $item;
                }
            }
            $insertItems = array_values ($insertItems);
            $log['item'] = $insertItems;

            RecLuckyDrawLog::create ($log);
//            throw new Exception('something was wrong');

            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }

        return ['choose' => $chooseItem, 'special' => $scrapItem];
    }

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
        if (Lock::getVal('week_end')['value'] == 1) {
            Common::res(['code' => 1, 'msg' => '金豆结算中，请稍后再试！']);
        }

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

                (new \app\api\service\User())->change ($user_id, $data, '使用抽奖券抽奖');
            }

            // 发放奖励
            if ($chooseItem['type'] == self::SCRAP) {
                $userScrap = (new UserExt)->readMaster ()->where('user_id', $user_id)->find ();
                $userScrapUpdate = ['scrap' => bcadd ($userScrap['scrap'], 1)];
                if ($userScrap['scrap_time']) {
                    $userScrapUpdate['scrap_time'] = null;
                }
                $added = UserExt::where('id', $userScrap['id'])->update($userScrapUpdate);
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