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

    public function animal()
    {
        return $this->hasOne('CfgAnimal', 'id', 'animal');
    }

    public static function getLeftLotteryTimes($user_id)
    {
        $nums = UserExt::where('user_id', $user_id)->value('animal_lottery');

        if ($nums > self::MAX) {
            return 0;
        }

        return (int) bcsub(self::MAX, $nums);
    }

    public static function lottery($type, $user_id)
    {
        $nums = UserExt::where('user_id', $user_id)->value('animal_lottery');

        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        if ($nums >= (int)$config['lottery']['max']) {
            Common::res(['code' => 1, 'msg' => '今日召唤数已用完']);
        }
        $typeItem = null;
        foreach ($config['lottery']['types'] as $value) {
            if ($value['type'] == $type) {
                $typeItem = $value;
            }
        }
        if (empty($typeItem)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        $currency = UserCurrency::getCurrency($user_id);
        if ($currency['panacea'] < $typeItem['panacea']) {
            Common::res(['code' => 1, 'msg' => '灵丹不够哦']);
        }

        $list = self::all();
        $array = range (0, bcsub ($typeItem['times'], 1));
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

        $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id', '*', ['user_id' => $user_id]);

        $animalDict = CfgAnimal::getDictList((new CfgAnimal()), $animalIds, 'id');

        Db::startTrans();
        try {
            $updateNums = bcadd($nums, $typeItem['number']);
            $extUpd = UserExt::where('user_id', $user_id)->update(['animal_lottery' => $updateNums]);
            if (empty($extUpd)) {
                throw new Exception('更新失败');
            }
            UserManor::where('user_id', $user_id)->update(['day_lottery_times' => $updateNums]);

            $inserts = [];
            $updateNum = 0;
            $updatedNum = 0;
            $hasNew = false;
            $endTime = date('Y-m-d H:i:s', strtotime('+1days'));
            foreach ($choose as $key => $value) {
                if (array_key_exists($key, $userAnimalDict)) {
                    // 更新
                    $updateNum ++;
                    $id = $userAnimalDict[$key]['id'];
                    $updated = UserAnimal::where('id', $id)->update([
                        'scrap' => bcadd($userAnimalDict[$key]['scrap'], $value['number'])
                    ]);
                    $update = empty($updated) ? 0: 1;
                    $updatedNum += $update;
                    $value['new'] = false;
                } else {
                    // 写入
                    $item = [
                        'user_id' => $user_id,
                        'animal_id' => $value['animal'],
                        'scrap' => $value['number'],
                        'level' => 1,
                    ];
                    array_push($inserts, $item);
                    $value['new'] = true;
                    $hasNew = true;
                }
                if (array_key_exists($value['animal'], $animalDict)) {
                    $value['animal_info'] = $animalDict[$value['animal']];
                }
                $choose[$key] = $value;

                UserManorLog::recordLottery($user_id, $animalDict[$key], $value['number']);
                UserAnimalBox::addScrap($animalDict[$key], $user_id, $endTime, $value['number'] / 10);
            }

            if ($updateNum) {
                if ($updateNum != $updatedNum) {
                    throw new Exception('更新失败');
                }
            }

            if ($inserts) {
                (new UserAnimal())->saveAll($inserts);
            }

            (new \app\api\service\User())->change($user_id, ['panacea' => -$typeItem['panacea']], '召唤宠物');
            UserManorLog::recordPanacea($user_id, -$typeItem['panacea'], sprintf('召唤宠物%s次', $typeItem['times']));
//            throw new Exception('something was wrong');
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();
//            throw $exception;
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }

        return ['list' => $choose, 'has_new' => $hasNew];
    }
}