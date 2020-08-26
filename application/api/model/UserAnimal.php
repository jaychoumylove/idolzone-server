<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;

class UserAnimal extends Base
{
    public static function lvUp($uid, $animal)
    {
        $userAnimal = UserAnimal::where('user_id', $uid)
            ->where('animal_id', $animal)
            ->find();

        $currentLevel = $userAnimal['level'];
        $nextLevel = bcadd($currentLevel, 1);
        $cfglv = CfgAnimalLevel::where('animal_id', $animal)
            ->where('level', $nextLevel)
            ->find();

        if (empty($cfglv)) {
            Common::res(['code' => 1, 'msg' => '已经到达最高等级了']);
        }

        $leftScrap = bcsub($userAnimal['scrap'], $cfglv['number']);
        if ($leftScrap < 0) {
            Common::res(['code' => 1, 'msg' => '碎片不够哦']);
        }

        $updated = UserAnimal::where('id', $userAnimal['id'])->update(['scrap' => $leftScrap]);
        if (empty($updated)) {
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }
    }

    public static function getOutput($uid, $type)
    {
        $output = 0;

        $userAnimals = self::where('user_id', $uid)->select();
        if (is_object($userAnimals)) $userAnimals = $userAnimals->toArray();
        if (empty($userAnimals)) return $output;

        $animalIds = array_column($userAnimals, 'animal_id');
        $outputAnimals = CfgAnimal::where('id', 'in', $animalIds)
            ->where('type', $type)
            ->select();
        if (is_object($outputAnimals)) $outputAnimals = $outputAnimals->toArray();
        if (empty($outputAnimals)) return $output;

        $outputIds = array_column($outputAnimals, 'id');
        $userOutputAnimals = array_filter($userAnimals, function ($item) use ($outputIds) {
            return in_array($item['animal_id'], $outputIds);
        });

        if (empty($userOutputAnimals)) return $output;

        foreach ($userOutputAnimals as $key => $userOutputAnimal) {
            $level = CfgAnimalLevel::where('animal_id', $userOutputAnimal['animal_id'])
                ->where('level', $userOutputAnimals['level'])
                ->find();

            $output += $level['data'];
        }

        return $output;
    }
}