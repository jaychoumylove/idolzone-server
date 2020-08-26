<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Exception;

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

        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        if (array_key_exists($type, $config['type']) == false) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        $currency = UserCurrency::getCurrency($user_id);
        if ($currency['panacea'] < $config['type'][$type]['panacea']) {
            Common::res(['code' => 1, 'msg' => '灵丹不够哦']);
        }

        $list = self::all();
        $array = range (0, bcsub ($config['type'][$type]['number'], 1));
        $choose = [];
        foreach ($array as $it) {
            $chooseItem = Common::lottery ($list);
            if (is_object($chooseItem)) $chooseItem = $chooseItem->toArray();

            if (array_key_exists($chooseItem['animal'], $choose)) {
                $choose[$chooseItem['animal']]['number'] = bcadd($chooseItem['number'], $choose[$chooseItem['animal']]['number']);
            } else {
                $choose[$chooseItem['animal']] = $chooseItem;
            }
        }

        $animalIds = array_keys($choose);

        $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id');

        Db::startTrans();
        try {
            $inserts = [];
            $updateNum = 0;
            $updatedNum = 0;
            foreach ($choose as $key => $value) {
                if (array_key_exists($key, $userAnimalDict)) {
                    // 更新
                    $updateNum ++;
                    $id = $userAnimalDict[$key]['id'];
                    $updated = UserAnimal::where('id', $id)->update([
                        'scrap' => bcadd($userAnimalDict[$key]['scrap'], $choose['number'])
                    ]);
                    $update = empty($updated) ? 0: 1;
                    $updatedNum += $update;
                } else {
                    // 写入
                    $item = [
                        'user_id' => $user_id,
                        'animal' => $value['animal'],
                        'scrap' => $value['number'],
                        'level' => 1,
                    ];
                    array_push($inserts, $item);
                }
            }

            if ($updateNum) {
                if ($updateNum != $updatedNum) {
                    throw new Exception('更新失败');
                }
            }

            if ($inserts) {
                UserAnimal::saveAll($inserts);
            }

            (new \app\api\service\User())->change($user_id, ['panacea' => -$config['type'][$type]['panacea']]);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }

        return $choose;
    }
}