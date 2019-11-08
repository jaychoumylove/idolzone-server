<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\service\User as UserService;
use app\base\service\Common;
use think\Db;

class UserCurrency extends Base
{
    public static function getCurrency($uid)
    {
        $item = self::get(['uid' => $uid]);
        if (!$item) {
            $item = self::create([
                'uid' => $uid,
                'coin' => 0,
                'stone' => 0,
                'trumpet' => 0,
            ]);
        }
        unset($item['id']);
        unset($item['create_time']);
        unset($item['uid']);
        return $item;
    }

    /**送给他人 */
    public static function sendToOther($self, $other, $num, $type)
    {
        if (!$self || !$other || $self == $other) Common::res(['code' => 1, 'msg' => '操作失败']);
        Db::startTrans();
        try {
            $userService = new UserService();

            $userService->change($self, [$type => -$num], '赠送给' . User::where('id', $other)->value('nickname'));

            $userService->change($other, [$type => $num], '收到来自' . User::where('id', $self)->value('nickname') . '赠送的');

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
