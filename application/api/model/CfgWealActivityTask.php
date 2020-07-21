<?php

namespace app\api\model;

use app\base\model\Base;

class CfgWealActivityTask extends Base
{
    const DAY = 'DAY';
    const SUM = 'SUM';

    const ON = 'ON';
    const OFF = 'OFF';

    // 累计任务
    const SUM_COUNT      = 'SUM_COUNT'; // 累计贡献任务key
    const USE_POINT      = 'USE_POINT'; // 累计使用积分key
    const USE_FOLLOWER   = 'USE_FOLLOWER'; // 累计使用鲜花key
    const USE_STONE      = 'USE_STONE'; // 累计使用砖石key
    const INVITE         = 'INVITE'; // 累计邀请新人key
    const RECHARGE       = 'RECHARGE'; // 累计充值key
    const FANS_CLUB_MASS = 'FANS_CLUB_MASS'; // 粉丝团集结key

    // 每日任务
    const WEIBO_SUPER   = 'WEIBO_SUPER'; // 每日微博超话任务key
    const WEIBO_RE_POST = 'WEIBO_RE_POST';// 每日微博转发任务key

    public static function getTaskByKey($key)
    {
        return self::get (['key' => $key]);
    }

    public static function getCheckTask($key)
    {
        $info = self::getTaskByKey ($key);
        if ($info['status'] == self::OFF) {
            return false;
        }

        return $info;
    }

    public function getList($uid)
    {
        $list = self::where('status', self::ON)->order ('sort','asc')->select ();
        if (is_object ($list)) $list = $list->toArray ();

        $taskIds = array_column ($list, 'id');
        $taskRec = RecWealActivityTask::where ('user_id', $uid)
            ->whereIn ('task_id', $taskIds)
            ->select ();

        if (is_object ($taskRec)) $taskRec = $taskRec->toArray ();

        $taskRecDict = array_column ($taskRec, null, 'task_id');

        foreach ($list as $key => $value) {
            $value['done_times'] = 0;
            $value['status']     = 0;
            if (array_key_exists ($value['id'], $taskRecDict)) {
                $recTask = $taskRecDict[$value['id']];

                $value['done_times'] = $recTask['done_times'];
                $value['status']     = $recTask['done_times'] >= $value['done'] ? 1 : 0;

                if ($recTask['is_settle_times'] == 1) {
                    $value['status'] = 2;
                }
            }
            $list[$key] = $value;
        }
        return $list;
    }
}
