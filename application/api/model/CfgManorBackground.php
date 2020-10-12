<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class CfgManorBackground extends Base
{
    const ON = 'ON';
    const OFF = 'OFF';

    public function getLockDataAttr($value)
    {
        return json_decode($value, true);
    }

    public function getStyleAttr($value)
    {
        return json_decode($value, true);
    }

    public static function unlockWithCurrency($uid, array $lockData)
    {
        $type = $lockData['key'];
        $task = RecUserBackgroundTask::get(['user_id' => $uid, 'type' => $type]);
        if (empty($task)) {
            return "贡献鲜花不足";
        }

        if ($task['sum'] < $lockData['number']) {
            $diff = bcsub($lockData['number'], $task['sum']);
            if ($diff > 10000) {
                $diff = bcdiv($diff, 10000, 1) . '万';
            }
            return "还差" . $diff. "鲜花";
        }

        return true;
    }

    public static function unlockWithLevel($uid, $data)
    {
        $userLevel = (int)CfgUserLevel::getLevel($uid);
        return $userLevel >= (int)$data['number'];
    }

    public static function unlockWithWeekRank($uid, array $lockData)
    {
        $userStar = UserStar::get(['user_id' => $uid]);
        if (empty($userStar)) {
            return "本周贡献度不够哦";
        }

        if ($userStar['thisweek_count'] < $lockData['number']) {
            $diff = bcsub($lockData['number'], $userStar['thisweek_count']);
            if ($diff > 10000) {
                $diff = bcdiv($diff, 10000, 1) . '万';
            }
            return "还差" . $diff . '本周贡献';
        }

        return true;
    }

    public static function unlockWithDayRank($uid, array $lockData)
    {
        $currentTime = time();
        $now = date ('Y-m-d H:i:s', $currentTime);
        $limit = $lockData['limit'];
        if ($now < $limit['start']) {
            return "活动尚未开始";
        }
        if ($now > $limit['end']) {
            return "活动已结束";
        }

        $userStar = UserStar::get(['user_id' => $uid]);
        if (empty($userStar)) {
            return "本日贡献度不够哦";
        }

        if ($userStar['thisday_count'] < $lockData['number']) {
            $diff = bcsub($lockData['number'], $userStar['thisday_count']);
            if ($diff > 10000) {
                $diff = bcdiv($diff, 10000, 1) . '万';
            }
            return "还差" . $diff . '本日贡献';
        }

        return true;
    }


    public static function unlockActive($uid, array $lockData)
    {
        $currentTime = time();
        $now = date ('Y-m-d H:i:s', $currentTime);
        $limit = $lockData['limit'];
        if ($now < $limit['start']) {
            return "活动尚未开始";
        }
        if ($now > $limit['end']) {
            return "活动已结束";
        }

        $type = $lockData['key'];
        $task = RecUserBackgroundTask::get(['user_id' => $uid, 'type' => $type]);
        if (empty($task)) {
            return "贡献鲜花不足";
        }

        if ($task['sum'] < $lockData['number']) {
            $diff = bcsub($lockData['number'], $task['count']);
            if ($diff > 10000) {
                $diff = bcdiv($diff, 10000, 1) . '万';
            }
            return "还差" . $diff. "鲜花";
        }

        RecUserBackgroundTask::where(['user_id' => $uid, 'type' => $type])->update([
            'count' => Db::raw('count-'.$lockData['number'])
        ]);

        return true;
    }

    public static function unlockWithLucky($uid, array $lockData)
    {
        $currentTime = time();
        $now = date ('Y-m-d H:i:s', $currentTime);
        $limit = $lockData['limit'];
        if ($now < $limit['start']) {
            return "活动尚未开始";
        }
        if ($now > $limit['end']) {
            return "活动已结束";
        }

        $max = $lockData['number'];

        $propsMap = [
            'user_id' => $uid,
            'prop_id' => $lockData['prop_id']
        ];
        $propNum = (new UserProp())->readMaster ()
            ->where($propsMap)
            ->where('status', 0)
            ->count ();

        if ($propNum < $max) {
            return '抽奖券不足';
        }

        // 消耗抽奖券
        $updated = (new UserProp)->where($propsMap)
            ->where('status', 0)
            ->limit ($max)
            ->order ([
                'create_time' => 'asc',
                'id' => 'asc'
            ])
            ->update([
                'status' => 1,
                'use_time' => time ()
            ]);

        if (empty($updated) || $updated < $max) {
            return "请稍后再试";
        }

        return true;
    }
}