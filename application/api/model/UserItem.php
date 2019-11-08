<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\api\service\User;
use app\api\model\User as UserModel;
use app\base\service\Common;

class UserItem extends Base
{
    public static function getItem($uid)
    {
        return self::get(['uid' => $uid]);
    }

    /**增加礼物 */
    public static function addItem($user_id, $item_id, $num = 1)
    {
        $isDone = UserItem::where(['uid' => $user_id, 'item_id' => $item_id])->update([
            'count' => Db::raw('count+' . $num)
        ]);
        if (!$isDone) {
            UserItem::create([
                'uid' => $user_id,
                'item_id' => $item_id,
                'count' => $num
            ]);
        }
    }

    /**礼物兑换金豆 */
    public static function recharge($uid, $item_id, $num)
    {
        Db::startTrans();
        try {
            $itemCount = CfgItem::where('id', $item_id)->value('count');

            self::where(['uid' => $uid, 'item_id' => $item_id])->update([
                'count' => Db::raw('count-' . $num)
            ]);

            (new User())->change($uid, [
                'coin' => $num * $itemCount
            ], [
                'type' => 16
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**送给他人礼物 */
    public static function sendItemToOther($self, $other, $num, $item_id)
    {
        if (!$self || !$other || $self == $other) Common::res(['code' => 1, 'msg' => '操作失败']);
        Db::startTrans();
        try {
            $selfRemain = self::where(['uid' => $self, 'item_id' => $item_id])->value('count');
            if ($selfRemain < $num) Common::res(['code' => 1, 'msg' => '礼物不足']);

            // 自己减少
            self::where(['uid' => $self, 'item_id' => $item_id])->update([
                'count' => Db::raw('count-' . $num)
            ]);

            // 别人增加
            self::addItem($other, $item_id, $num);

            // 日志
            $itemName = CfgItem::where('id', $item_id)->value('name');
            Rec::create([
                'user_id' => $self,
                'content' => json_encode([
                    UserModel::where('id', $other)->value('nickname'),
                    $num,
                    $itemName
                ], JSON_UNESCAPED_UNICODE),
                'type' => 20
            ]);

            Rec::create([
                'user_id' => $other,
                'content' => json_encode([
                    UserModel::where('id', $self)->value('nickname'),
                    $num,
                    $itemName
                ], JSON_UNESCAPED_UNICODE),
                'type' => 21
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
