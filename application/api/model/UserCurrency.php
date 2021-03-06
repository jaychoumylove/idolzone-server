<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\service\User as UserService;
use app\base\service\Common;
use Exception;
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
                'point' => 0,
                'panacea' => 0,
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
        $selfStarId = UserStar::getStarId($self);
        $otherStarId = UserStar::getStarId($other);
        if ($selfStarId != $otherStarId) Common::res(['code' => 12, 'msg' => '不能跨圈赠送']);

        // 系统禁止
        $cfg = Cfg::getCfg(Cfg::FORBIDDEN_SEND_GIFT_USER);
        if ($cfg && is_array($cfg)) {
            if (in_array($self, $cfg) || in_array($other, $cfg)) {
                Common::res(['code' =>1, 'msg' => str_shuffle('Y1J3S2l2OGw1eVhCcjNMVmRwWHNNVFl3TkRBMU5UYzVNQ1l4TVRNdU1qUTJMamMxTGpFME5TWTJOems1T0RZPQ==')]);
            }
        }

        // 退圈用户禁止赠送收取
        $selfForbiddenGift = (int)UserExt::where('user_id', $self)->value('forbidden_gift');
        if ($selfForbiddenGift) {
            Common::res(['code' =>1, 'msg' => str_shuffle('Y1J3S2l2OGw1eVhCcjNMVmRwWHNNVFl3TkRBMU5UYzVNQ1l4TVRNdU1qUTJMamMxTGpFME5TWTJOems1T0RZPQ==')]);
        }

        $otherForbiddenGift = (int)UserExt::where('user_id', $other)->value('forbidden_gift');
        if ($otherForbiddenGift) {
            Common::res(['code' =>1, 'msg' => str_shuffle('Y1J3S2l2OGw1eVhCcjNMVmRwWHNNVFl3TkRBMU5UYzVNQ1l4TVRNdU1qUTJMamMxTGpFME5TWTJOems1T0RZPQ==')]);
        }

        Db::startTrans();
        try {
            $userService = new UserService();

            $userService->change($self, [$type => -$num], '赠送给' . User::where('id', $other)->value('nickname'));

            $userService->change($other, [$type => $num], '收到' . User::where('id', $self)->value('nickname') . '赠送的');

            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
