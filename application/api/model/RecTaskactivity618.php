<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class RecTaskactivity618 extends Base
{
    //根据任务id插入或更新任务日志
    public static function addOrEdit($uid, $task_id,$done_times=1,$is_settle_times=0)
    {
        if(Cfg::is618activeStart()==false)return;
        $check = self::where(['user_id' => $uid, 'task_id' => $task_id])
            ->update([
                'done_times' => Db::raw('done_times+'.$done_times),
                'is_settle_times' => Db::raw('is_settle_times+'.$is_settle_times)
            ]);
        if (!$check) {
            self::create(['user_id' => $uid, 'task_id' => $task_id, 'done_times' => $done_times, 'is_settle_times' => $is_settle_times]);
        }

        if($is_settle_times>0){
            RecActivity618::addRec([
                'user_id' => $uid,
                'content' => '恭喜你获得了'.$is_settle_times.'个福袋和'.$is_settle_times.'点幸运值',
                'blessing_num' => $is_settle_times,
                'lucky_value' => $is_settle_times,
            ]);
        }

    }


}
