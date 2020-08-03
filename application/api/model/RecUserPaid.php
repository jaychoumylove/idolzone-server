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
            ->update([
                'count' => CfgPaid::DAY_EMPTY,
                'is_settle' => CfgPaid::DAY_EMPTY
            ]);
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

            $currencyReward = array_filter ($reward, function($item) {
                return $item['type'] == CfgPaid::CURRENCY;
            });
            $propReward = array_filter ($reward, function($item) {
                return $item['type'] == CfgPaid::PROP;
            });
            $earn = [];
            if ($isSum) {
                $num = $this->settleSum ($map, (float) $paid['count']);
                if (empty($num)) {
                    throw new Exception('未达到领取要求', 3);
                }
                foreach ($currencyReward as $key => $value) {
                    $earn[$value['key']] = bcmul ($value['number'], $num);
                }
            }
            if ($isDay) {
                $res = $this->settleDay ($map, (float) $paid['count']);
                if (empty($res)) {
                    throw new Exception('未达到领取要求', 3);
                }
                foreach ($currencyReward as $key => $value) {
                    $earn[$value['key']] = $value['number'];
                }
            }

            $typeMsgMap = [
                CfgPaid::SUM => '累计',
                CfgPaid::DAY => '每日',
            ];
            $msg = sprintf ('领取%s充值奖励', $typeMsgMap[$paid_type]);

            (new \app\api\service\User())->change ($user_id, $earn, $msg);

            if ($propReward) {
                // 新增用户道具
                foreach ($propReward as $key => $value) {
                    $exist = UserProp::where('user_id', $user_id)
                        ->where ('prop_id', $value['key'])
                        ->find ();

                    if (empty($exist)) {
                        // 首次翻倍
                        $extra = $value['number'];
                    }
                    $number = $value['number'];
                    if ($isSum) {
                        $number = bcmul ($number, $num);
                    }
                    if (isset($extra)) {
                        $number = bcadd ($number, $extra);
                    }
                    UserProp::addProp ($user_id, $value['key'], $number);
                }
            }
//            throw new Exception('something was wrong');

            Db::commit ();
        }catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
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
     * @param $cfgNum
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    private function settleDay($map, $cfgNum)
    {
        $paid = self::where($map)->find ();
        if ((float)$paid['count'] > $cfgNum || $paid['is_settle'] > 0) {
            if ((int)$paid['is_settle'] != CfgPaid::DAY_EMPTY) {
                return false;
            }
            $data = ['is_settle' => CfgPaid::DAY_FINISH];
            $updated = self::where(['id' => $paid['id']])->update($data);

            return (bool)$updated;
        }

        return false;
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
        if ($lastNum >= $cfgNum) {
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
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function setTask($user_id, $number)
    {
        self::finishTask ($user_id, $number, CfgPaid::DAY);
        self::finishTask ($user_id, $number, CfgPaid::SUM);
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
            $data = ['count' => bcadd ($exist['count'], $number)];
            self::where('id', $exist['id'])->update($data);
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