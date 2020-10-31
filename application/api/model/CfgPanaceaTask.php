<?php

namespace app\api\model;

use app\base\model\Base;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

class CfgPanaceaTask extends Base
{
    const DAY = 'DAY';
    const SUM = 'SUM';
    const ONCE = 'ONCE';
    const RANK = 'RANK';

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

    // 榜单任务
    const FLOWER_RANK = 'FLOWER_RANK';// 每日鲜花榜top10
    const PK_RANK = 'PK_RANK'; // pk榜top10

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
        $taskRec = RecPanaceaTask::where ('user_id', $uid)
            ->whereIn ('task_id', $taskIds)
            ->select ();

        if (is_object ($taskRec)) $taskRec = $taskRec->toArray ();

        $taskRecDict = array_column ($taskRec, null, 'task_id');

        foreach ($list as $key => $value) {
            $value['done_times'] = 0;
            $value['status']     = 0;
            $value['extra'] = json_decode($value['extra'], true);
            if($value['type'] == self::RANK) {
                $extra = $value['extra'];
                $first = $extra[0];
                $last = $extra[count($extra) - 1];
                $value['reward'] = $first .'-'. $last;
            }
            if (array_key_exists ($value['id'], $taskRecDict)) {
                $recTask = $taskRecDict[$value['id']];

                $value['done_times'] = $recTask['done_times'];
                $value['status']     = $recTask['done_times'] >= $value['done'] ? 1 : 0;

                if ($value['type'] == self::SUM) {
                    $value = self::supportSumItem ($value);
                }

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
     * @return mixed
     */
    public static function supportSumItem($value)
    {
        $last = $value['done_times'];
        $limit = $value['done'];
        $mul = 0;
        while ($last >= $limit) {
            $last -= $limit;
            $mul ++;
        }

        if ($mul) {
            $value['reward'] = bcmul ($value['reward'], $mul);
            if ($value['extra']) {
                if ($value['extra']['with_reward']) {
                    $extra = $value['extra'];
                    $extra['with_reward']['data'] = bcmul ($extra['with_reward']['data'], $mul);
                    $value['extra'] = $extra;
                }
            }
        }

        return $value;
    }

    /**
     * @param $value
     * @param $uid
     * @return mixed
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function supportOnceItem($value, $uid)
    {
        // 无记录
        // 有记录 未达成
        // 有记录 有达成
        // 有记录 但是已经顶级
        if ($value['key'] == self::LEVEL) {
            $number = CfgUserLevel::getLevel ($uid);
            $rewardMap = RecPanaceaTask::getLevelRewardMap ();
        }

        if (false !== strpos ($value['key'], CfgWealActivityTask::BADGE)) {
            $stype = self::getBadgeTypeByKey ($value['key']);
            $number = BadgeUser::getUserTypeBadgeOffset ($uid, $stype);
            $rewardMap = RecPanaceaTask::getBadgeRewardMap ($value['key']);
        }

        $hasRec = true;
        if (empty($value['done_times'])) {
            $hasRec = false;
        }

        $maxDone = count ($rewardMap);
        $nowDone = $number;
        if ($hasRec) {
            $value['status'] = 0;
            $start = $value['done_times'];
            $length = bcsub ($nowDone, $value['done_times']);
            if ($length) {
                $value['status'] = 1;
                $value['done'] = $nowDone;
                $value['reward'] = RecPanaceaTask::getRewardByOnce ($rewardMap, $start, $length);
            } else {
                if ($nowDone == $maxDone) {
                    $value['status'] = 2;
                    $value['done'] = $maxDone;
                    $value['reward'] = 0;
                } else {
                    $value['done'] = bcadd ($value['done_times'], 1);
                    $value['reward'] = RecPanaceaTask::getRewardByOnce ($rewardMap, $start, 1);
                }
            }
        } else {
            $value['status'] = 1;
            $value['done'] = $maxDone;
            $start = 0;
            $length = $nowDone;
            if (empty($length)) {
                // 还没有徽章/等级
                $value['status'] = 0;
                $value['done_times'] = 0;
                $value['done'] = bcadd ($nowDone, 1);
                $value['reward'] = RecPanaceaTask::getRewardByOnce ($rewardMap, $start, 1);
            } else {
                $value['done'] = $nowDone;
                $value['done_times'] = $nowDone;
                $value['reward'] = RecPanaceaTask::getRewardByOnce ($rewardMap, $start, $nowDone);
            }
        }
        return $value;
    }
}
