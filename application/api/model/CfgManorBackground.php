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

    public static function unlockWithCurrency($uid, array $lockData)
    {
        $userCurrency = UserCurrency::getCurrency($uid);
        if (is_object($userCurrency)) $userCurrency = $userCurrency->toArray();
        $status = false;
        if (array_key_exists($lockData['key'], $userCurrency)) {
            $number = (int)$userCurrency[$lockData['key']];
            $status = $number >= $lockData['number'];

            if ($status) {
                (new \app\api\service\User())->change($uid, [$lockData['key'] => -$lockData['number']], '解锁庄园背景');
            }
        }

        return $status;
    }

    public static function unlockWithLevel($uid, $data)
    {
        $userLevel = (int)CfgUserLevel::getLevel($uid);
        return $userLevel >= (int)$data['number'];
    }
}