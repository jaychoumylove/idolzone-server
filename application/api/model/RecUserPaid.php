<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

class RecUserPaid extends Base
{
    public static function cleanDay()
    {
        self::where('paid_type', CfgPaid::DAY)
            ->where ('count', '>', 0)
            ->update(['count' => 0]);
    }

    public static function checkDayPaid($user_id)
    {
        $map = [
            'user_id' => $user_id,
            'type' => CfgPaid::DAY
        ];

        $paid = self::where($map)->find ();

        if (empty($paid)) return false;
        if (empty((float)$paid['count'])) return false;

        return true;
    }

    /**
     * 领取任务奖励
     *
     * @param $paid_id
     * @param $user_id
     * @return array|bool|float|int|mixed|object|\stdClass|string|null
     * @throws DbException
     */
    public function settle($paid_id, $user_id)
    {
        $paid = CfgPaid::get ($paid_id);
        if (empty($paid)) {
            Common::res (['code' => 1, 'msg' => '福利已失效']);
        }

        if ($paid['status'] == CfgPaid::OFF) {
            Common::res (['code' => 1, 'msg' => '福利已关闭']);
        }

        $paid_type = $paid['type'];

        Db::startTrans ();
        try {
            $isSum = $paid_type == CfgPaid::SUM;
            $isDay = $paid_type == CfgPaid::DAY;

            $map = compact ('user_id', 'paid_type');
            $reward = $paid['reward'];
            if ($isSum) {
                $num = $this->settleSum ($map, (float) $paid['count']);
                if (empty($num)) {
                    throw new Exception('未达到领取要求', 3);
                }
                $earn = [];
                foreach ($reward['currency'] as $key => $value) {
                    $earn[$key] = bcmul ($value, $num);
                }
            }
            if ($isDay) {
                $res = $this->settleDay ($map);
                if (empty($res)) {
                    throw new Exception('未达到领取要求', 3);
                }
                $earn = $reward['currency'];
            }

            $typeMsgMap = [
                CfgPaid::SUM => '累计',
                CfgPaid::DAY => '每日',
            ];
            $msg = sprintf ('领取%s充值奖励', $typeMsgMap[$paid_type]);

            (new \app\api\service\User())->change ($user_id, $earn, $msg);

            if (array_key_exists ('item', $reward)) {
                // 新增用户道具
                foreach ($reward['item'] as $key => $value) {
                    UserProp::addProp ($user_id, $key, $value);
                }
            }
            throw new Exception('something was wrong');

            Db::commit ();
        }catch (\Throwable $throwable) {
            Db::rollback ();
            throw $throwable;
            $msg = "领取失败,请稍后再试";
            if ($throwable->getCode () == 3) $msg = $throwable->getMessage ();
            Common::res (['code' => 1, 'msg' => $msg]);
        }

        return $earn;
    }

    /**
     * 完成每日任务
     *
     * @param $map
     * @return bool
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    private function settleDay($map)
    {
        $paid = self::where($map)->find ();
        $data = ['count' => 1];
        if ((int)$paid['count'] > 0) {
            return false;
        }
        $updated = self::where(['id' => $paid['id']])->update($data);

        return (bool)$updated;
    }


    /**
     * 完成累计任务 - 返回完成次数
     *
     * @param $map
     * @param $cfgNum
     * @return int
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    private function settleSum($map, $cfgNum)
    {
        $paid = self::where($map)->find ();

        $rewardNum = 0;
        $lastNum = (float)$paid['count'];
        while ($lastNum >= $cfgNum) {
            $lastNum -= $cfgNum;
            $rewardNum ++;
        }

        if (empty($rewardNum)) return 0;

        $data = [
            'count' => $lastNum,
        ];
        $updated = self::where(['id' => $paid['id']])->update($data);

        if (empty($updated)) return 0;

        return $rewardNum;
    }

    /**
     * 生成|更新 家族用户任务记录
     *
     * @param $user_id
     * @param $number
     * @param $type
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function setTask($user_id, $number, $type)
    {
        $check = CfgPaid::getCheckType ($type);

        if (empty($check)) return;

        self::finishTask ($user_id, $number, $type);
    }

    /**
     * 生成|更新 累计任务记录
     * @param $user_id
     * @param $paid_type
     * @param $number
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected static function finishSum($user_id, $paid_type, $number)
    {
        $map = compact ('user_id', 'paid_type');
        $exist = self::where($map)->find ();
        if (empty($exist)) {
            $data = ['count' => $number];
            self::create (array_merge ($data, $map));
        } else {
            $data = ['count' => bcadd ($exist['count'], $number)];
            self::where('id', $exist['id'])->update($data);
        }
    }

    /**
     * 生成|更新 每日任务记录
     * @param     $user_id
     * @param     $paid_type
     * @param int $number
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected static function finishDay($user_id, $paid_type, $number = 1)
    {
        $map = compact ('user_id', 'paid_type');
        $exist = self::where($map)->find ();
        $data = ['count' => $number];
        if (empty($exist)) {
            self::create (array_merge ($data, $map));
        } else {
            if ($exist['count'] != $number) {
                self::where('id', $exist['id'])->update($data);
            }
        }
    }

    /**
     * 生成|更新 任务日志记录
     *
     * @param        $user_id
     * @param int    $number
     * @param string $type
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function finishTask($user_id, $number = 1, $type = CfgPaid::SUM)
    {
        switch ($type) {
            case CfgPaid::SUM:
                self::finishSum ($user_id, $type, $number);
                break;
            case CfgPaid::DAY:
                self::finishDay ($user_id, $type, $number);
                break;
            default:
                break;
        }
    }
}