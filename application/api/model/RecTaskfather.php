<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class RecTaskfather extends Base
{
    /**
     * 增加任务记录（进度） 
     * @param int $user_id
     * @param int|array $task_ids 任务id
     * @param int $times
     */
    public static function addRec($user_id, $task_ids, $times = 1)
    {
        //只有加入了师徒的用户才记录
        if(Father::where('son_uid',$user_id)->count()){
            
            if (gettype($task_ids) == 'integer' || gettype($task_ids) == 'string') {
                $task_ids = [$task_ids];
            }
    
            foreach ($task_ids as $task_id) {
                $isDone = self::where('user_id', $user_id)->where('task_id', $task_id)
                    ->update(['done_times' => Db::raw('done_times+' . $times)]);
    
                if (!$isDone) {
                    try {
                        
                        self::create([
                            'user_id' => $user_id,
                            'task_id' => $task_id,
                            'done_times' => $times,
                        ]);
                        
                    } catch (\Exception $e) {}
                }
            }
        }
    }

    /**
     * 任务完成进度
     */
    public static function getUserRec($user_id)
    {
        $recTask = self::where('user_id', $user_id)->select();
        $recTaskData = [];
        foreach ($recTask as $value) {
            $recTaskData[$value['task_id']] = $value;
        }

        return $recTaskData;
    }

    /**将任务进度设置为已领取 */
    public static function settle($uid, $task_id)
    {
        $isDone = self::where('user_id', $uid)->where('task_id', $task_id)->update(['is_settle' => 1]);
        if (!$isDone) Common::res(['code' => 1, 'msg' => '任务领取失败！']);
    }

    /**每日签到 */
    public static function checkIn($uid)
    {   
        if(Father::where('son_uid',$uid)->count()){
            
            $taskIds = [1, 12, 23, 34];
    
            foreach ($taskIds as $taskId) {
                $c = self::where('task_id', $taskId)->where('user_id', $uid)->whereTime('update_time', 'd')->count();
    
                if (!$c) {
                    self::addRec($uid, $taskId, 1);
                }
            }
        }
    }

    /**
     * 同步任务记录
     * @param int $user_id
     * @param int|array $task_ids 任务id
     * @param int $times
     */
    public static function syncRec($user_id, $task_ids, $times = 1)
    {
        if (gettype($task_ids) == 'integer' || gettype($task_ids) == 'string') {
            $task_ids = [$task_ids];
        }

        foreach ($task_ids as $task_id) {
            $isExist = self::where('user_id', $user_id)->where('task_id', $task_id)->count();
            if ($isExist) {
                self::where('user_id', $user_id)->where('task_id', $task_id)
                    ->update(['done_times' => $times]);
            } else {
                self::create([
                    'user_id' => $user_id,
                    'task_id' => $task_id,
                    'done_times' => $times,
                ]);
            }
        }
    }

    /**同步一些数据 */
    public static function sync($user_id)
    {
        // 农场产量
        $coin = UserSprite::where('user_id', $user_id)->value('total_speed_coin');
        self::syncRec($user_id,  [5, 16, 27, 38], $coin);

        // 粉丝等级
        $level = CfgUserLevel::getLevel($user_id);
        self::syncRec($user_id, [10, 21, 32, 43], $level);

        // 是否加入粉丝团
        $fid = FanclubUser::where('user_id', $user_id)->value('fanclub_id');
        if ($fid) self::syncRec($user_id, 11);

        // 粉丝团任务完成情况
        $settleCount = RecTaskfanclub::where('fanclub_id', $fid)->where('is_settle', 1)->count();
        self::syncRec($user_id, [22, 33, 44], $settleCount);
    }
}
