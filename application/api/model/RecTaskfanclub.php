<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class RecTaskfanclub extends Base
{
    /**
     * 增加任务记录（进度） 
     * @param int $user_id
     * @param int|array $task_ids 任务id
     * @param int $times
     */
    public static function addRec($fanclub_id, $task_ids, $times = 1)
    {
        if (gettype($task_ids) == 'integer' || gettype($task_ids) == 'string') {
            $task_ids = [$task_ids];
        }
        
        foreach ($task_ids as $task_id) {
            $taskType = CfgTaskfanclub::where('id', $task_id)->value('type');
            try {
                self::create([
                    'fanclub_id' => $fanclub_id,
                    'task_id' => $task_id,
                    'task_type' => $taskType,
                ]);
            
            } catch (\Exception $e) {}
        }
    }

    /**
     * 任务完成进度
     */
    public static function getRec($fanclub_id, $type)
    {
        $recTask = self::where('fanclub_id', $fanclub_id)->where('task_type', $type)->select();
        $recTaskData = [];
        foreach ($recTask as $value) {
            $recTaskData[$value['task_id']] = $value;
        }

        return $recTaskData;
    }

    /**将任务进度设置为已领取 */
    public static function settle($fanclub_id, $task_id, $task_type)
    {
        $isDone = self::where('fanclub_id', $fanclub_id)->where('task_id', $task_id)->where('is_settle', 0)->update(['is_settle' => 1,'settle_time' => date('Y-m-d H:i:s')]);
        
        if (!$isDone){
            try {
                
                self::create([
                    'fanclub_id' => $fanclub_id,
                    'task_id' => $task_id,
                    'task_type' => $task_type,
                    'is_settle' => 1,
                    'settle_time' => date('Y-m-d H:i:s'),
                ]);
                
            } catch (\Exception $e) {
                
                Common::res(['code' => 1, 'msg' => '上周奖励已领取过了，请到日志中查看']);
            }
            
        }
    }
}
