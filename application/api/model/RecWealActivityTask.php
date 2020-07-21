<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

class RecWealActivityTask extends Base
{
    //根据任务id插入或更新任务日志
    public static function addOrEdit($uid, $task_id,$done_times=1,$is_settle_times=0)
    {
        if(Cfg::checkWealActive()==false)return;
        $check = self::where(['user_id' => $uid, 'task_id' => $task_id])
            ->update([
                'done_times' => Db::raw('done_times+'.$done_times),
                'is_settle_times' => Db::raw('is_settle_times+'.$is_settle_times)
            ]);
        if (!$check) {
            self::create(['user_id' => $uid, 'task_id' => $task_id, 'done_times' => $done_times, 'is_settle_times' => $is_settle_times]);
        }

        if($is_settle_times>0){
            RecWealActivity::addRec([
                'user_id' => $uid,
                'content' => CfgWealActivityTask::where('id',$task_id)->value('name'),
                'bag_num' => $is_settle_times,
                'lucky' => $is_settle_times,
            ]);
        }
    }

    public static function cleanDay()
    {
        $dayTask = CfgWealActivityTask::where('type', CfgWealActivityTask::DAY)->select ();
        if (is_object ($dayTask)) $dayTask = $dayTask->toArray ();

        $dayTaskIds = array_column ($dayTask, 'id');

        self::where('task_id', 'in', $dayTaskIds)->update([
            'done_times' => 0,
            'is_settle_times' => 0
        ]);
    }

    /**
     * 领取任务奖励
     * @param $task_id
     * @param $uid
     * @return array|bool|float|int|mixed|object|\stdClass|string|null
     * @throws DbException
     */
    public function settle($task_id, $uid)
    {
        $task = CfgWealActivityTask::get ($task_id);
        if (empty($task)) {
            Common::res (['code' => 1, 'msg' => '任务已失效']);
        }

        Db::startTrans ();
        try {
            $isSum = $task['type'] == CfgWealActivityTask::SUM;
            $isDay = $task['type'] == CfgWealActivityTask::DAY;
            $isOnce = $task['type'] == CfgWealActivityTask::ONCE;

            $map = [
                'user_id' => $uid,
                'task_id' => $task_id,
            ];
            if ($isSum) {
                $num = $this->settleSum ($map, $task['done']);
                if (empty($num)) {
                    throw new Exception('未达到完成任务要求', 3);
                }
                $earn = bcmul ($num, $task['reward'], 2);
            }
            if ($isDay) {
                $res = $this->settleDay ($map);
                if (empty($res)) {
                    throw new Exception('未达到完成任务要求', 3);
                }
                $earn = $task['reward'];
            }
            if ($isOnce) {
                $earn = $this->settleOnce ($map, $task['key']);
                if (empty($earn)) {
                    throw new Exception('未达到完成任务要求', 3);
                }
            }

            $res = UserExt::luckyChange ($uid, $earn);
            if (empty($res)) {
                throw new Exception('重复领取', 3);
            }

            RecWealActivity::addRec([
                'user_id' => $uid,
                'content' => CfgWealActivityTask::where('id',$task_id)->value('name'),
                'lucky' => $earn,
            ]);

//            throw new Exception('something was wrong');

            Db::commit ();
        }catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            $msg = "完成失败,请稍后再试";
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
        $task = self::where($map)->find ();
        $data = ['is_settle_times' => 1];
        if ((int)$task['is_settle_times'] !== 0) {
            return false;
        }
        $updated = self::where(['id' => $task['id']])->update($data);

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
        $task = self::where($map)->find ();

        $rewardNum = 0;
        $lastNum = $task['done_times'];
        while ($lastNum >= $cfgNum) {
            $lastNum -= $cfgNum;
            $rewardNum ++;
        }

        if (empty($rewardNum)) return $rewardNum;

        $data = [
            'done_times' => $lastNum,
        ];
        $updated = self::where(['id' => $task['id']])->update($data);

        if (empty($updated)) return 0;

        return $rewardNum;
    }

    /**
     * @param $map
     * @param $key
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    private function settleOnce($map, $key)
    {
        $task = self::where($map)->find ();
        $start = $task ? $task['done_times']: 1;

        $reward = 0;
        $data = [];
        if ($key == CfgWealActivityTask::LEVEL) {
            $level = CfgUserLevel::getLevel($map['user_id']);
            $typeMap = $this->getLevelRewardMap ();
            $reward = $this->getRewardByOnce($typeMap, $start, $level);
            $data = ['done_times' => $level];
        }

        if (false !== strpos ($key, CfgWealActivityTask::BADGE)) {
            $type = CfgWealActivityTask::getBadgeTypeByKey ($key);
            $badge = BadgeUser::getUserTypeBadgeOffset ($map['user_id'], $type);
            $typeMap = $this->getBadgeRewardMap ($key);
            $reward = $this->getRewardByOnce($typeMap, $start, $badge);
            $data = ['done_times' => $badge];
        }

        if (empty($reward)) return 0;

        if ($task) {
            $updated = self::where('id', $task['id'])->update($data);

            if ((bool) $updated == false) {
                throw new Exception('重复领取', 3);
            }
        } else {
            self::create (array_merge ($data, $map));
        }

        return $reward;
    }

    /**
     * 获取等级奖励
     *
     * @return array
     */
    public static function getLevelRewardMap()
    {
        return [
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.2,
            0.2,
            0.2,
            0.3,
            0.5,
            3,
        ];
    }

    /**
     * 获取等级奖励
     *
     * @param $key
     * @return array
     */
    public static function getBadgeRewardMap($key)
    {
        $index = CfgWealActivityTask::getBadgeTypeByKey ($key);

        $map = [
            2 => [
                0.1,
                0.1,
                0.1,
                0.1,
                0.1,
                0.2,
                0.2,
                0.6,
                3,
            ]
        ];

        if (array_key_exists ($index, $map)) {
            return $map[$index];
        }
    }


    /**
     * 获取once任务奖励
     *
     * @param $map
     * @param $start
     * @param $number
     * @return int
     */
    public static function getRewardByOnce($map, $start, $number)
    {
        $reward = 0;
        while ($number > $start) {
            $index = bcsub ($start, 1);
            if (array_key_exists ($index, $map)) {
                $reward = bcadd ($map[$index], $reward, 1);
                $start ++;
            }
        }

        return $reward;
    }

    /**
     * 生成|更新 家族用户任务记录
     *
     * @param $uid
     * @param $number
     * @param $key
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function setTask($uid, $number, $key)
    {
        $status = Cfg::checkActiveByPathInBtnGroup (Cfg::WEAL_ACTIVE_PATH);

        if (empty($status)) return;

        $task = CfgWealActivityTask::getCheckTask ($key);

        if (empty($task)) return;

        self::finishTask ($uid, $task['id'], $number, $task['type']);
    }

    /**
     * 生成|更新 累计任务记录
     * @param $user_id
     * @param $task_id
     * @param $number
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected static function finishSum($user_id, $task_id, $number)
    {
        $map = compact ('user_id', 'task_id');
        $exist = self::where($map)->find ();
        if (empty($exist)) {
            $data = ['done_times' => $number];
            self::create (array_merge ($data, $map));
        } else {
            $data = ['done_times' => bcadd ($exist['done_times'], $number)];
            self::where('id', $exist['id'])->update($data);
        }
    }

    /**
     * 生成|更新 每日任务记录
     * @param     $user_id
     * @param     $task_id
     * @param int $number
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected static function finishDay($user_id, $task_id, $number = 1)
    {
        $map = compact ('user_id', 'task_id');
        $exist = self::where($map)->find ();
        $data = ['done_times' => $number];
        if (empty($exist)) {
            self::create (array_merge ($data, $map));
        } else {
            if ($exist['done_times'] != $number) {
                self::where('id', $exist['id'])->update($data);
            }
        }
    }

    /**
     * 生成|更新 任务日志记录
     *
     * @param        $user_id
     * @param        $task_id
     * @param int    $number
     * @param string $type
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function finishTask($user_id, $task_id, $number = 1, $type = CfgWealActivityTask::SUM)
    {
        switch ($type) {
            case CfgWealActivityTask::SUM:
                self::finishSum ($user_id, $task_id, $number);
                break;
            case CfgWealActivityTask::DAY:
                self::finishDay ($user_id, $task_id, $number);
                break;
            default:
                break;
        }
    }
}
