<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Exception;
use Throwable;

class CfgLuckyDraw extends Base
{
    const CURRENCY = 'currency';
    const SCRAP = 'scrap';
    const ANIMAL = 'animal';

    public static function startFifty($user_id)
    {
        if (Lock::getVal('week_end')['value'] == 1) {
            Common::res(['code' => 1, 'msg' => '金豆结算中，请稍后再试！']);
        }

        $config = Cfg::getCfg (Cfg::RECHARGE_LUCKY);

        $able = Cfg::checkMultipleDrawAble ($config['multiple_draw']);
        if (empty($able)) {
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }
        $max = $config['multiple_draw']['multiple'];

        $prop_id = Prop::where('key', Prop::LUCKY_DRAW)->value ('id');

        $propsMap = compact ('user_id', 'prop_id');
        $propNum = (new UserProp())->readMaster ()
            ->where($propsMap)
            ->where('status', 0)
            ->count ();

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
            $currencyMap = ['stone', 'coin', 'flower', 'old_coin', 'trumpet' ,'panacea'];
            $earn = [];
            $scraps = [$scrapItem['number']];
            $animal = [];
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

                if ($item['type'] == self::ANIMAL) {
                    $left = array_key_exists($item['key'], $animal) ? $animal[$item['key']]: 0;
                    $animal[$item['key']] = (int)bcadd($left, $item['number']);
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

            if ($animal) {
                $animalIds = array_keys($animal);
                $animalModel = new UserAnimal();
                $userAnimalDict = $animalModel::getDictList($animalModel, $animalIds, 'animal_id', '*', ['user_id' => $user_id]);
                $create = [];
                $shouldUpdatedNum = 0;
                $updatedNum = 0;
                foreach ($animal as $key => $value) {
                    if (array_key_exists($key, $userAnimalDict)) {
                        $shouldUpdatedNum ++;
                        $update = [
                            'scrap' => bcadd($userAnimalDict[$key]['scrap'], $value)
                        ];
                        $updated = $animalModel->where('user_id', $user_id)
                            ->where('animal_id', $key)
                            ->update($update);
                        if ($updated) {
                            $updatedNum ++;
                        }
                    } else {
                        array_push($create, [
                            'user_id' => $user_id,
                            'animal_id' => $key,
                            'scrap' => $value,
                            'level' => 1
                        ]);
                    }
                }
                if ($updatedNum != $shouldUpdatedNum) {
                    Common::res(['code' => 1, 'msg' => '请稍后再试']);
                }

                if ($create) {
                    $animalModel->insertAll($create);
                }
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
        } catch (Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }

        return ['choose' => $chooseItem, 'special' => $scrapItem];
    }

    public static function startMore($user_id, $index, $times)
    {
        if (Lock::getVal('week_end')['value'] == 1) {
            Common::res(['code' => 1, 'msg' => '金豆结算中，请稍后再试！']);
        }

        $config = Cfg::getCfg (Cfg::RECHARGE_LUCKY);

        if ($times!=$config['multiple_draw'][$index]['multiple']) {
            $times=$config['multiple_draw'][$index]['multiple'];
        }
        $able = Cfg::checkMultipleDrawAble ($config['multiple_draw'][$index]);
        if (empty($able)) {
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }
        $max = $times;

        $prop_id = Prop::where('key', Prop::LUCKY_DRAW)->value ('id');

        $propsMap = compact ('user_id', 'prop_id');
        $propNum = (new UserProp())->readMaster ()
            ->where($propsMap)
            ->where('status', 0)
            ->count ();

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
            $currencyMap = ['stone', 'coin', 'flower', 'old_coin', 'trumpet' ,'panacea'];
            $earn = [];
            $animal = [];
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

                if ($item['type'] == self::ANIMAL) {
                    $left = array_key_exists($item['key'], $animal) ? $animal[$item['key']]: 0;
                    $animal[$item['key']] = (int)bcadd($left, $item['number']);
                }
            }
            if ($earn) {
                (new \app\api\service\User())->change ($user_id, $earn, '使用抽奖券抽奖');
            }

            if ($animal) {
                $animalIds = array_keys($animal);
                $animalModel = new UserAnimal();
                $userAnimalDict = $animalModel::getDictList($animalModel, $animalIds, 'animal_id', '*', ['user_id' => $user_id]);
                $create = [];
                $shouldUpdatedNum = 0;
                $updatedNum = 0;
                foreach ($animal as $key => $value) {
                    if (array_key_exists($key, $userAnimalDict)) {
                        $shouldUpdatedNum ++;
                        $update = [
                            'scrap' => bcadd($userAnimalDict[$key]['scrap'], $value)
                        ];
                        $updated = $animalModel->where('user_id', $user_id)
                            ->where('animal_id', $key)
                            ->update($update);
                        if ($updated) {
                            $updatedNum ++;
                        }
                    } else {
                        array_push($create, [
                            'user_id' => $user_id,
                            'animal_id' => $key,
                            'scrap' => $value,
                            'level' => 1
                        ]);
                    }
                }
                if ($updatedNum != $shouldUpdatedNum) {
                    Common::res(['code' => 1, 'msg' => '请稍后再试']);
                }

                if ($create) {
                    $animalModel->insertAll($create);
                }
            }

            // 记录抽奖信息
            $log = [
                'user_id' => $user_id,
                'lucky_draw' => $luckyDraw['id'],
                'type' => RecLuckyDrawLog::MULTIPLE
            ];
            foreach ($chooseItem as $item) {
                $number = bcmul ($item['number'], $item['times']);
                $item['number'] = $number;
                unset($item['times']);
                $insertItems[$item['key']] = $item;
            }
            $insertItems = array_values ($insertItems);
            $log['item'] = $insertItems;

            RecLuckyDrawLog::create ($log);
//            throw new Exception('something was wrong');

            Db::commit ();
        } catch (Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }

        return ['choose' => $chooseItem];
    }

    public function getRewardAttr($value)
    {
        return json_decode ($value, true);
    }

    public static function getLuckyDraw()
    {
        return self::get (['current' => 1]);
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
                $currencyMap = ['stone', 'coin', 'flower', 'old_coin', 'trumpet' ,'panacea'];
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

            if ($chooseItem['type'] == self::ANIMAL) {
                $map = [
                    'user_id' => $user_id,
                    'animal_id' => $chooseItem['key']
                ];
                $exist = UserAnimal::get($map);
                if (empty($exist)) {
                    $data = [
                        'scrap' => $chooseItem['number'],
                        'level' => 1,
                    ];

                    UserAnimal::create(array_merge($data, $map));
                } else {
                    $added = UserAnimal::where('id', $exist['id'])
                        ->update([
                            'scrap' => bcadd($exist['scrap'], $chooseItem['number'])
                        ]);
                    if (empty($added)) Common::res (['code' => 1, 'msg' => '请稍后再试']);
                }
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
        } catch (Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }

        return $chooseItem;
    }
}