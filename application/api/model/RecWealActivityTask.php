<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

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
}
