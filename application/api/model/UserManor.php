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

    public static function animalOutput($uid)
    {
        $selfManor = self::get(['user_id' => $uid]);
        $currentTime = time();
        $diffTime = bcsub($currentTime, $selfManor['last_output_time']);
        if ($diffTime < self::MIN_OUTPUT_TIME) return;

        $output = UserAnimal::getOutput($uid, CfgAnimal::OUTPUT);
        if (empty($output)) return;

        $maxTime = self::LIMIT_OUTPUT_HOURS * 60 * 60;
        $outputMax = bcmul($output, $maxTime);
        if ($diffTime > $maxTime) {
            // 最多只能存储8小时产豆
            $addCount = $outputMax;
        } else {
            $num = bcdiv($diffTime, self::MIN_OUTPUT_TIME);

            $addCount = bcmul($output, $num);
        }

        $update = [];
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
}