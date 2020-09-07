<?php


namespace app\api\model;


use app\base\model\Base;

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
            return "贡献鲜花不足";
        }

        return true;
    }

    public static function unlockWithLevel($uid, $data)
    {
        $userLevel = (int)CfgUserLevel::getLevel($uid);
        return $userLevel >= (int)$data['number'];
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
            return "贡献鲜花不足";
        }

        return true;
    }
}