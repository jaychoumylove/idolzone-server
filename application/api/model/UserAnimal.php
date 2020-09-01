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

        $updateData =[
            'scrap' => $leftScrap,
            'level' => $nextLevel,
        ];

        $updated = UserAnimal::where('id', $userAnimal['id'])->update($updateData);
        if (empty($updated)) {
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }
    }

    public static function getOutput($uid, $type)
    {
        $number = 0;

        $userAnimals = self::where('user_id', $uid)->select();
        if (is_object($userAnimals)) $userAnimals = $userAnimals->toArray();
        if (empty($userAnimals)) {
            return $number;
        }

        $animalIds = array_column($userAnimals, 'animal_id');
        $outputAnimals = CfgAnimal::where('id', 'in', $animalIds)->select();
        if (is_object($outputAnimals)) $outputAnimals = $outputAnimals->toArray();
        if (empty($outputAnimals)) {
            return $number;
        }

        $outputIds = array_column($outputAnimals, 'id');
        $userOutputAnimals = array_filter($userAnimals, function ($item) use ($outputIds) {
            return in_array($item['animal_id'], $outputIds);
        });

        if (empty($userOutputAnimals)) {
            return $number;
        }

        $outputType = [
            CfgAnimal::OUTPUT => 'output',
            CfgAnimal::STEAL => 'steal',
        ];
        foreach ($userOutputAnimals as $key => $userOutputAnimal) {
            $level = CfgAnimalLevel::where('animal_id', $userOutputAnimal['animal_id'])
                ->where('level', $userOutputAnimal['level'])
                ->find();

            $number += $level[$outputType[$type]];
        }
        return $number;
    }

    public static function countOutputSecond($output, $number)
    {
        $outputSec = bcdiv($output, self::MIN_OUTPUT_TIME, 1);
        return ceil(bcdiv($number, $outputSec, 2));
    }

    public static function getOutputNumber($user_id, $diffTime, $default = 0)
    {
        $addCount = 0;
        $output = self::getOutput($user_id, CfgAnimal::OUTPUT);
        if (empty($output)) return $addCount;

        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        $limit_output_hours = $config['max_output_hours'];
        $min_output_time = $config['min_output_seconds'];

        $maxTime = $limit_output_hours * 60 * 6;
        $outputMax = bcmul($output, $maxTime);
        if ($diffTime > $maxTime) {
            // 最多只能存储8小时产豆
            $addCount = $outputMax;
        } else {
            $num = bcdiv($diffTime, $min_output_time);

            $addCount = bcmul($output, $num);
            if ($default) {
                $addCount = bcadd($addCount, $default);
                if ($addCount > $outputMax) $addCount = $outputMax;
            }
        }

        return $addCount;
    }

    public static function getStealLeft($uid)
    {
        $stealLeft = self::getOutput($uid, CfgAnimal::STEAL);

        $manor = UserManor::get(['user_id' => $uid]);
        $useTimes = (int)$manor['day_steal'];

        return $stealLeft > $useTimes ? (int)bcsub($stealLeft, $useTimes): 0;
    }
}