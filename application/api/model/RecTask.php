<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class RecTask extends Base
{
    /**
     * 增加任务记录（进度） 
     * @param int $user_id
     * @param int|array $task_ids 任务id
     * @param int $times
     */
    public static function addRec($user_id, $task_ids, $times = 1)
    {
        if (gettype($task_ids) == 'integer' || gettype($task_ids) == 'string') {
            $task_ids = [$task_ids];
        }

        foreach ($task_ids as $task_id) {
            $isDone = self::where('user_id', $user_id)->where('task_id', $task_id)
                ->update(['done_times' => Db::raw('done_times+' . $times)]);

            if (!$isDone) {
                $taskType = Task::where('id', $task_id)->value('type');
                self::create([
                    'user_id' => $user_id,
                    'task_id' => $task_id,
                    'task_type' => $taskType,
                    'done_times' => $times,
                ]);
            }
        }
    }

    /**
     * 任务完成进度
     */
    public static function getUserRec($user_id, $type)
    {
        $recTask = self::where('user_id', $user_id)->where('task_type', $type)->select();
        $recTaskData = [];
        foreach ($recTask as $value) {
            $recTaskData[$value['task_id']] = $value;
        }

        return $recTaskData;
    }

    /**将任务进度设置为已领取 */
    public static function settle($uid, $task_id)
    {
        if (
            $task_id == 19 || // 看视频
            $task_id == 20    // 看广告
        ) {
            // 可重复完成
            self::addRec($uid, $task_id);

            // 最多完成的次数
            $maxTimes = Task::where('id', $task_id)->value('times');
            // 已完成的次数
            $doneTimes = self::where('user_id', $uid)->where('task_id', $task_id)->value('done_times');

            if ($doneTimes >= $maxTimes) {
                // 设置为已完成 不可继续完成
                self::where('user_id', $uid)->where('task_id', $task_id)->update(['is_settle' => 1]);
            }
        } else {
            $isDone = self::where('user_id', $uid)->where('task_id', $task_id)->update(['is_settle' => 1]);
            if (!$isDone) Common::res(['code' => 1, 'msg' => '任务领取失败！']);
        }
    }

    /**每日签到 */
    public static function checkIn($uid)
    {
        $done = self::where('user_id', $uid)->where('task_id', 1)->value('done_times');
        if (!$done) self::addRec($uid, 1);
        
        if (input('platform') == 'APP') {
            // APP签到
            $done = self::where('user_id', $uid)->where('task_id', 22)->value('done_times');
            if (!$done) self::addRec($uid, 22);
        }
    }
}
