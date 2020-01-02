<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;
use app\api\controller\v1\Badge;

class UserProp extends Base
{
    public function Prop()
    {
        return $this->belongsTo('Prop', 'prop_id', 'id');
    }

    /**
     * 增加道具
     * @param int $prop_id 
     * @param int $num 增加数量 
     */
    public static function addProp($user_id, $prop_id, $num)
    {
        $insert = [];
        for ($i = 0; $i < $num; $i++) {
            $insert[] = [
                'user_id' => $user_id,
                'prop_id' => $prop_id,
            ];
        }

        self::insertAll($insert);
    }

    public static function getList($uid, $order = 'id desc', $where = '1=1')
    {
        $list = self::with('Prop')->where('user_id', $uid)->where($where)->where('status=0')->order($order)->select();

        // 检查是否已过期
        foreach ($list as &$value) {
            $value['title'] = $value['prop']['name'] . '(失效时间' . $value['end_time'] . ')';
            if ($value['status'] == 0 && strtotime($value['end_time']) < time()) {
                // 购买的道具仅限当天使用
                $value['status'] = 2;
                self::where('id', $value['id'])->update(['status' => 2]);
            }
        }
        return $list;
    }

    /**积分兑换 */
    public static function exchangePoint($uid, $id, $count)
    {
        $prop = Prop::get($id);
        if (!$prop || $prop['delete_time'] != NULL) Common::res(['code' => 3, 'msg' => '奖品无法兑换']);

        $res = [];
        $status = 0;
        Db::startTrans();
        try {
            if (in_array($id, [11, 12, 13])) { //冬至，圣诞，元旦徽章
                $count = 1;  //只能买一个
                $badge = [11 => 55, 12 => 56, 13 => 57];
                $status = 1;  //已使用
                if (BadgeUser::where(['uid' => $uid])->where('badge_id', 'in', $badge[$id])->value('count(1)'))
                    Common::res(['code' => 3, 'msg' => '你已经兑换过了']);

                BadgeUser::addRec($uid, 0, 1, $badge[$id]); //增加徽章
            }

            for ($i = 1; $i <= $count; $i++) {
                (new User())->change($uid, [
                    'point' => (-1) * $prop['point'],
                ], $prop['title']);

                self::create([
                    'user_id' => $uid,
                    'prop_id' => $id,
                    'end_time' => date('Y-m-d H:i:s', strtotime('+7 day')),
                    'status' => $status,
                ]);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $res;
    }

    /**道具使用 */
    public static function useIt($uid, $userprop_id)
    {
        $userProp = self::get($userprop_id);
        if (!$userProp || $userProp['delete_time'] != NULL || $userProp['status'] != 0) Common::res(['code' => 3, 'msg' => '无法使用']);

        $res = [];
        Db::startTrans();
        try {
            switch ($userProp['prop_id']) {
                case 1:
                case 2:
                    // 充值券
                    self::where('id', $userprop_id)->update(['status' => 1]);

                    break;
                case 10: //兑换随机金豆
                    // 能量福袋
                    $awards = [10000, 12000, 15000, 20000];
                    (new User())->change($uid, [
                        'coin' => $awards[mt_rand(0, 3)],
                    ], '使用金豆福袋');

                    self::where('id', $userprop_id)->update(['status' => 1]);

                    break;

                default:
                    break;
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $res;
    }

    /**
     * 给一张双倍优惠券
     */
    public static function giveRechargeTicketEveryday($uid)
    {
        $propId = 2;

        $isExit = self::where('user_id', $uid)->where('prop_id', $propId)->where('status', 0)->where('end_time', '>', date('Y-m-d H:i:s'))->find();
        if ($isExit) return '您有未使用的双倍券，现在充值就享双倍鲜花哦';

        $isExit = self::where('user_id', $uid)->where('prop_id', $propId)->whereTime('create_time', 'd')->find();
        if ($isExit) return '今日双倍券已领，您可以明日再来';

        self::addProp($uid, $propId, 1);
        return '送您一张双倍券，现在充值就享双倍鲜花哦';
    }
}
