<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Exception;
use Throwable;

class UserAnimal extends Base
{

    public function Animal()
    {
        return  $this->belongsTo('cfg_animal', 'animal_id', 'id')->field('id,name,image,type,adventure_type');
    }

    public static function lvUp($uid, $animal)
    {
        $animalInfo = CfgAnimal::get($animal);
        if (empty($animalInfo)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        $userAnimal = (new UserAnimal())
            ->readMaster()
            ->where('user_id', $uid)
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

        $isNormal = $animalInfo['type'] == CfgAnimal::NORMAL;
        $isSecret = $animalInfo['type'] == CfgAnimal::SECRET;
        $isStarSecret = $animalInfo['type'] == CfgAnimal::STAR_SECRET;
        $isSuperSecret = $animalInfo['type'] == CfgAnimal::SUPER_SECRET;

        if ($isNormal) {
            $scrap_num = $userAnimal['scrap'];
        }
        if ($isSecret || $isStarSecret) {
            $scrap_num = (new UserExt())
                ->readMaster()
                ->where('user_id', $uid)
                ->value ('scrap');
        }
        if ($isSuperSecret) {
            $scrap_num = (new UserCurrency())->readMaster()
                ->where('uid', $uid)
                ->value('panacea', 0);
        }

        $leftScrap = bcsub($scrap_num, $cfglv['number']);
        if ($leftScrap < 0) {
            Common::res(['code' => 1, 'msg' => '碎片不够哦']);
        }

        $updateData =[
            'level' => $nextLevel,
        ];
        if ($isNormal) {
            $updateData['scrap'] = $leftScrap;
        }

        Db::startTrans();
        try {
            $updated = UserAnimal::where('id', $userAnimal['id'])->update($updateData);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            if ($isNormal) {
                UserManorLog::recordTwelveLvUp($uid, $animalInfo, $nextLevel, $cfglv['number']);
            }

            if ($isSuperSecret) {
                (new \app\api\service\User())->change($uid, ['panacea' => -$cfglv['number']], '升级灵宠');

                UserManorLog::recordSecret($uid, $animalInfo, $nextLevel, $cfglv['number'], false);
            }

            if ($isSecret || $isStarSecret) {
                $scrapUpdated = UserExt::where('user_id', $uid)
                    ->where('scrap', $scrap_num)
                    ->update(['scrap' => $leftScrap]);
                if (empty($scrapUpdated)) {
                    throw new Exception('更新失败');
                }

                UserManorLog::recordSecret($uid, $animalInfo, $nextLevel, $cfglv['number'], false);
            }

            Db::commit();
        }catch (Throwable $throwable) {
            Db::rollback();

            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }
    }

    public static function unlock($uid, $animal)
    {
        $animalInfo = CfgAnimal::get($animal);
        if (empty($animalInfo)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }
        if (in_array($animalInfo['type'], [CfgAnimal::STAR_SECRET,CfgAnimal::SECRET, CfgAnimal::SUPER_SECRET]) == false) {
            //
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }
//        $isSecret = $animalInfo['type'] == CfgAnimal::SECRET;
//        $isStarSecret = $animalInfo['type'] == CfgAnimal::STAR_SECRET;
        $isSuperSecret = $animalInfo['type'] == CfgAnimal::SUPER_SECRET;

        if ($animalInfo['star_id'] || $isSuperSecret) {
            $star = UserStar::getStarId($uid);
            if ($star != $animalInfo['star_id']) {
                Common::res(['code' => 1, 'msg' => '暂未开放']);
            }
        }

        $exist = UserAnimal::get(['animal_id' => $animal, 'user_id' => $uid]);
        if ($exist) {
            Common::res(['code' => 1, 'msg' => '已解锁']);
        }

        $currentLevel = 1;
        $cfglv = CfgAnimalLevel::where('animal_id', $animal)
            ->where('level', $currentLevel)
            ->find();

        if (empty($cfglv)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        if ($isSuperSecret) {
            $scrap_num = (new UserCurrency())->readMaster()
                ->where('uid', $uid)
                ->value('panacea', 0);
        } else {
            $scrap_num = UserExt::where('user_id', $uid)->value ('scrap');
        }

        $leftScrap = bcsub($scrap_num, $cfglv['number']);
        if ($leftScrap < 0) {
            Common::res(['code' => 1, 'msg' => '碎片不够哦']);
        }

        Db::startTrans();
        try {
            $lvData = [
                'user_id' => $uid,
                'animal_id' => $animal,
                'level' => 1,
            ];
            if (in_array($animalInfo['type'], [CfgAnimal::STAR_SECRET, CfgAnimal::SUPER_SECRET])) {
                $lvData['use_image'] = $animalInfo['image'];
            }
            UserAnimal::create($lvData);

            if ($isSuperSecret) {
                (new \app\api\service\User())->change($uid, ['panacea' => -$cfglv['number']], '解锁灵宠');
            } else {
                $scrapUpdated = UserExt::where('user_id', $uid)
                    ->where('scrap', $scrap_num)
                    ->update(['scrap' => $leftScrap]);
                if (empty($scrapUpdated)) {
                    throw new Exception('更新失败');
                }
            }

            UserManorLog::recordSecret($uid, $animalInfo, 0, $cfglv['number'], true);

            UserManor::where('user_id', $uid)->update(['use_animal' => $animal]);

            Db::commit();
        }catch (Throwable $throwable) {
            Db::rollback();
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
        $limit_output_hours = UserManorBackground::getMaxHours((int)$config['max_output_hours'], $user_id);
        $min_output_time = $config['min_output_seconds'];

        $maxTime = $limit_output_hours * 60 * 60;
        $timer = bcdiv($maxTime, $min_output_time);
        $outputMax = bcmul($output, $timer);
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

    public static function exchange($uid, $type, $animal_id)
    {
        $config = Cfg::getCfg(Cfg::RECHARGE_LUCKY)['animal_exchange'];
        $status = Cfg::checkConfigTime($config);
        if (empty($status)) {
            Common::res(['code' => 1, 'msg' => '活动已过期']);
        }

        $typeItem = [];
        foreach ($config['rate'] as $item) {
            if ($item['type'] == $type) {
                $typeItem = $item;
            }
        }

        if (empty($typeItem)) {
            Common::res(['code' => 1, 'msg' => '请选择兑换方式']);
        }

        $animalInfo = CfgAnimal::get($animal_id);
        if ($animalInfo['type'] != CfgAnimal::NORMAL) {
            Common::res(['code' => 1, 'msg' => '只能兑换十二生肖宠物碎片哦']);
        }

        $now = time();
        $date = date('Y-m-d H:i:s', $now);
        $luckyNum =  (new UserProp())->readMaster()
            ->where('prop_id', $typeItem['lucky'])
            ->where('user_id', $uid)
            ->where('end_time', '>', $date)
            ->where('status', 0)
            ->where('use_time', 0)
            ->count();

        if ($luckyNum < $typeItem['lucky_num']) {
            Common::res(['code' =>1, 'msg' => '抽奖券不够哦']);
        }

        Db::startTrans();
        try {
            $userAnimal = UserAnimal::where('user_id', $uid)
                ->where('animal_id', $animal_id)
                ->find();
            if ($userAnimal) {
                UserAnimal::where('id', $userAnimal['id'])
                    ->update([
                        'scrap' => Db::raw('scrap+'. $typeItem['number'])
                    ]);
            } else {
                UserAnimal::create([
                    'user_id' => $uid,
                    'animal_id' => $animal_id,
                    'scrap' => $typeItem['number'],
                    'level' => 1,
                ]);
            }

            UserProp::where('prop_id', $typeItem['lucky'])
                ->where('user_id', $uid)
                ->where('end_time', '>', $date)
                ->where('status', 0)
                ->where('use_time', 0)
                ->order([
                    'id' => 'asc'
                ])
                ->limit($typeItem['lucky_num'])
                ->update([
                    'use_time' => $now,
                    'status' => 1
                ]);

            UserManorLog::recordWithNationalDayExchangeAnimal($uid, $animalInfo, $typeItem);

            Db::commit();
        }catch (Throwable $throwable){
            Db::rollback();
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }
    }

    public static function checkoutSecretImage($uid)
    {
        $animalInfo = CfgAnimal::get(['type' => CfgAnimal::STAR_SECRET]);
        if (empty($animalInfo)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }
        $animal = $animalInfo['id'];

        $exist = UserAnimal::get(['animal_id' => $animal, 'user_id' => $uid]);
        if (empty($exist)) {
            Common::res(['code' => 1, 'msg' => '您还没有该宠物哦']);
        }

        $starId = UserStar::getStarId($uid);
        if (empty($starId)) {
            Common::res(['code' => 1, 'msg' => '请先加入圈子']);
        }
        $starAnimal = CfgAnimal::get([
            'type' => CfgAnimal::STAR,
            'star_id' => $starId
        ]);
        if (empty($starAnimal)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        $update = [];
        if ($exist['use_image'] == $starAnimal['image']) {
            $update['use_image'] = $animalInfo['image'];
        } else {
            $update['use_image'] = $starAnimal['image'];
        }

        UserAnimal::where('id', $exist['id'])->update($update);
    }

    public static function checkSuperSecretImage($uid, $lv)
    {
        $starId = UserStar::getStarId($uid);
        if (empty($starId)) {
            Common::res(['code' => 1, 'msg' => '请先加入圈子']);
        }
        $animalInfo = CfgAnimal::get(['type' => CfgAnimal::SUPER_SECRET, 'star_id' => $starId]);
        if (empty($animalInfo)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }
        $animal = $animalInfo['id'];

        $exist = UserAnimal::get(['animal_id' => $animal, 'user_id' => $uid]);
        if (empty($exist)) {
            Common::res(['code' => 1, 'msg' => '您还没有该宠物哦']);
        }

        if ($exist['level'] < $lv) {
            Common::res(['code' => 1, 'msg' => '您还未解锁哦']);
        }

        $lvExist = CfgAnimalLevel::get(['level' => $lv, 'animal_id' => $animal]);
        if (empty($lvExist)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        if ($lvExist['image']['lock']) {
            Common::res(['code' => 1, 'msg' => '暂未开放哦']);
        }

        $update['use_image'] = $lvExist['image']['url'];

        UserAnimal::where('id', $exist['id'])->update($update);
    }
}