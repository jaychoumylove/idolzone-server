<?php

namespace app\api\model;

use app\base\model\Base;

class CfgWealActivityTask extends Base
{
    const DAY = 'DAY';
    const SUM = 'SUM';
    const ONCE = 'ONCE';

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

    //  单次任务
    const LEVEL = 'LEVEL';// 等级任务
    const BADGE = 'BADGE'; // 徽章任务 key

    public static function getTaskByKey($key)
    {
        return self::get (['key' => $key]);
    }

    /**
     * 获取徽章类型
     * @param $key
     * @return int
     */
    public static function getBadgeTypeByKey($key)
    {
        return (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
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

                if ($value['type'] == self::ONCE) {
                    $value = self::supportOnceItem ($value, $uid);
                }
            } else {
                if ($value['type'] == self::ONCE) {
                    $value['status'] = 1;
                    $value = self::supportOnceItem ($value, $uid);
                }
            }

            $list[$key] = $value;
        }
        return $list;
    }

    /**
     * @param $value
     * @param $uid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function supportOnceItem($value, $uid)
    {
        $isNew = false;
        if ($value['key'] == self::LEVEL) {
            $number = CfgUserLevel::getLevel ($uid);
            if (empty($value['done_times'])) {
                $isNew = true;
                $value['done_times'] = $number;
            }
            $value['done'] = CfgUserLevel::getMaxLevel();
        }
        if (false !== strpos ($value['key'], CfgWealActivityTask::BADGE)) {
            $stype = CfgWealActivityTask::getBadgeTypeByKey ($value['key']);
            $number = BadgeUser::getUserTypeBadgeOffset ($uid, $stype);
            if (empty($value['done_times'])) {
                $isNew = true;
                $value['done_times'] = $number;
            }
            $value['done'] = CfgBadge::where('stype', $stype)->count ();
        }
        if ((int) $value['done_times'] == (int) $value['done'] && empty($isNew)) {
            $value['reward'] = 0;
            $value['status'] = 2;
        } else {
            if ($value['key'] == self::LEVEL) {
                $rewardMap = RecWealActivityTask::getLevelRewardMap ();
            }
            if (false !== strpos ($value['key'], CfgWealActivityTask::BADGE)) {
                $rewardMap = RecWealActivityTask::getBadgeRewardMap ($value['key']);
            }
            $value['reward'] = RecWealActivityTask::getRewardByOnce ($rewardMap, $value['done_times'], $number);
        }

        return $value;
    }
}
