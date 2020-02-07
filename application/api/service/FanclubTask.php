<?php

namespace app\api\service;

use app\api\model\RecTaskfanclub as RecTask;
use app\api\model\CfgTaskfanclub as TaskModel;
use app\base\service\Common;
use think\Db;
use app\api\model\Fanclub;
use app\api\model\RecTaskfather;

class FanclubTask
{
    /**
     * 检查任务是否完成
     */
    public function checkTask($fanclub_id, $type)
    {
        // 任务列表
        $taskList = TaskModel::where('type', $type)->order('sort asc')->select();

        // 用户的任务完成进度
        $recTask = RecTask::getRec($fanclub_id, $type);
        foreach ($taskList as $key => &$task) {
            // 任务状态 0未完成 1已完成 2已领取
            $task['status'] = 0;
            // 完成次数
            $task['lastWeek_doneTimes'] = 0;
            $task['doneTimes'] = 0;

            if (isset($recTask[$task['id']])) {
                $task['lastWeek_doneTimes'] = $recTask[$task['id']]['lastweek_done_times'];
                $task['doneTimes'] = $recTask[$task['id']]['done_times'];

                if ($recTask[$task['id']]['is_settle']) {
                    // 已领取
                    $task['status'] = 2;
                } else 
                    if ($task['lastWeek_doneTimes'] >= $task['times']) {
                    // 已完成
                    $task['status'] = 1;
                }
            }
        }

        return $taskList;
    }


    /**
     * 任务奖励领取
     */
    public function settle($task_id, $uid)
    {
        $task = TaskModel::get($task_id);
        $fid = Fanclub::where('user_id', $uid)->value('id');
        if (!$fid) Common::res(['code' => 1, 'msg' => '你不在粉丝团或者没有权限！']);

        Db::startTrans();
        try {
            RecTask::settle($fid, $task_id);

            $update = [
                'coin' => $task['coin'],
                'flower' => $task['flower'],
                'stone' => $task['stone'],
                'trumpet' => $task['trumpet']
            ];
            (new User())->change($uid, $update, '完成任务');

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }

        return $update;
    }
}
