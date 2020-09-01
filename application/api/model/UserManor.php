<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Exception;
use Throwable;

class UserManor extends Base
{
    const MIN_OUTPUT_TIME = 10;
    const LIMIT_OUTPUT_HOURS = 8;
    const AUTO_LEVEL_UP = 2;
    const STEAL_NUMBER = 1000;

    public static function checkFistReward()
    {
        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        $startTime = strtotime($config['manor_start_time']);
        $timeStr = sprintf('+%s days', $config['manor_get_flower_reward_limit']);
        $endTime = strtotime($timeStr, $startTime);
        $currentTime = time();
        if ($currentTime < $startTime) {
            return false;
        }
        if ($currentTime > $endTime) {
            return false;
        }

        return true;
    }

    public static function getFlowerReward($uid)
    {
        $status = self::checkFistReward();
        if (empty($status)) {
            return 0;
        }

        $userStar = UserStar::get(['user_id' => $uid]);
        $totalFlower = $userStar['total_flower'];

        $num = (int) bcdiv($totalFlower, 1000000);

        if ($num) {
            (new \app\api\service\User())->change($uid, ['panacea' => $num], '往期鲜花收益');
        }

        return $num;
    }

    public static function animalOutput($uid)
    {
        $selfManor = self::get(['user_id' => $uid]);
        $currentTime = time();
        $diffTime = bcsub($currentTime, $selfManor['last_output_time']);
        if ($diffTime < self::MIN_OUTPUT_TIME) return;

        $addCount = UserAnimal::getOutputNumber($uid, $diffTime, $selfManor['count_left']);

        $update = [];
        if ($selfManor['count_left']) {
            $update['count_left'] = 0;
        }
        $update['day_count'] = bcadd($selfManor['day_count'], $addCount);
        $update['week_count'] = bcadd($selfManor['week_count'], $addCount);
        $update['sum'] = bcadd($selfManor['sum'], $addCount);
        $update['last_output_time'] = $currentTime;
        Db::startTrans();
        try {
            $updated = UserManor::where('id', $selfManor['id'])->update($update);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            (new \app\api\service\User())->change($uid, ['coin' => $addCount], '庄园金豆收益');
            Db::commit();
        }catch (Throwable $throwable) {
            Db::rollback();
            Common::res(['code' => 1, 'msg' => '请稍后再试']);
        }
    }

    public static function steal($uid, $steal_id)
    {
        $selfManor = self::get(['user_id' => $uid]);

        $stealNum = UserAnimal::getOutput($uid, CfgAnimal::STEAL);
        if (empty($stealNum)) return;

        if ($selfManor['day_steal'] >= $stealNum) {
            Common::res(['code' => 1, 'msg' => '今天偷取次数已经用完了哦']);
        }

        $stealManor = self::get(['user_id' => $steal_id]);

        $stealOutput = UserAnimal::getOutput($steal_id, CfgAnimal::OUTPUT);
        $currentTime = time();
        $diffTime = bcsub($currentTime, $stealManor['last_output_time']);
        $num = bcdiv($diffTime, self::MIN_OUTPUT_TIME);
        $addCount = bcmul($stealOutput, $num);
        if ($addCount < self::STEAL_NUMBER) {
            Common::res(['code' => 1, 'msg' => '已经被庄主收走咯']);
        }

        $second = bcdiv(self::STEAL_NUMBER, $stealOutput);
        $left = bcmod(self::STEAL_NUMBER, $stealOutput);
        $update = [];
        if ($second) {
            $update['last_output_time'] = bcsub($stealManor['last_output_time'], $second);
        }
        if ($left) {
            $update['count_left'] = bcadd($stealManor['count_left'], $left);
        }
        if (empty($update)) {
            Common::res(['code' => 1, 'msg' => '已经被庄主收走咯']);
        }

        Db::startTrans();
        try {
            $stealed = UserManor::where('id', $stealManor['id'])
                ->where('last_output_time', '<>', $stealManor['last_output_time'])
                ->update($update);
            if (empty($stealed))  {
                throw new Exception('已经被庄主收走咯');
            }

            (new \app\api\service\User())->change($uid, ['coin' => self::STEAL_NUMBER], '偷取庄园金豆');

            ManorStealLog::create([
                'user_id' => $uid,
                'steal_id' => $steal_id,
                'number' => self::STEAL_NUMBER
            ]);

            Db::commit();
        }catch (Throwable $throwable) {
            Db::rollback();
            Common::res(['code' => 1, 'msg' => '已经被庄主收走咯']);
        }
    }

    public static function getRandomStealUser($user_id)
    {
        $currentTime = time();

        $sql = 'SELECT * FROM `f_user_manor`
WHERE id >= (SELECT FLOOR( RAND()*((SELECT MAX(id) FROM f_user_manor)-(SELECT MIN(id) FROM f_user_manor))+(SELECT MIN(id) FROM f_user_manor)))
and user_id <> ';
        $sql .= $user_id;
        $sql .= ' ORDER BY id LIMIT 10;';

        $list = Db::query($sql);

        $userIds = array_column($list, 'user_id');
        $userDict = UserManor::getDictList((new User()), $userIds, 'id', 'nickname,id,avatarurl');
        foreach ($list as $key => $value ) {
            $list[$key]['user'] = array_key_exists($value['user_id'], $userDict) ? $userDict[$value['user_id']]: null;
        }

        return $list;
    }

    public static function animalChange($uid, $animalId)
    {
        $exist = UserAnimal::get(['user_id' => $uid, 'animal_id' => $animalId]);
        if (empty($exist)) {
            Common::res(['code' => 1, 'msg' => '你还没有这个宠物哦']);
        }

        UserManor::where('user_id', $uid)->update(['use_animal' => $animalId]);
    }
}