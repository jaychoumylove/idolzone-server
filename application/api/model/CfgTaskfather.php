<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class CfgTaskfather extends Base
{
    public static function getList($active, $uid)
    {
        RecTaskfather::sync($uid);

        // 任务列表
        $list = self::where('father_level', $active)->select();
        // 用户的任务完成进度
        $recTask = RecTaskfather::getUserRec($uid);
        foreach ($list as $key => &$task) {
            // 任务状态 0未完成 1已完成 2已领取
            $task['status'] = 0;
            // 完成次数
            $task['doneTimes'] = 0;

            if (in_array($task['id'], [3, 14, 25, 36, 11, 22, 33, 44])) {
                // 粉丝团
                $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
                if ($fid) {
                    $task['gopage'] = '/pages/fans/fans_club?fid=' . $fid;
                }
            }

            if (isset($recTask[$task['id']])) {
                $task['doneTimes'] = $recTask[$task['id']]['done_times'];

                if ($recTask[$task['id']]['is_settle']) {
                    // 已领取
                    $task['status'] = 2;
                } else 
                    if ($task['doneTimes'] >= $task['times']) {
                    // 已完成
                    $task['status'] = 1;
                }
            }
        }

        return $list;
    }
}
