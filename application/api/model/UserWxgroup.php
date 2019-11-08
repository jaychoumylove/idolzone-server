<?php

namespace app\api\model;

use app\api\model\User as UserModel;
use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class UserWxgroup extends Base
{

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**新增群用户 */
    public static function groupAddUser($gid, $uid)
    {
        $isFind = self::where('wxgroup_id', $gid)->where('user_id', $uid)->find();
        if (!$isFind) {
            $isFind = self::create([
                'user_id' => $uid,
                'wxgroup_id' => $gid,
            ]);

            // 群人数+1
            Wxgroup::where('id', $gid)->update(['member_count' => Db::raw('member_count+1')]);
        }
    }

    /**群集结状态 */
    public static function massStatus($gid)
    {
        // 群集结时间
        $massCfg = Cfg::getCfg('groupmass');
        // 集结开始时间
        $res['massStartTime'] = Wxgroup::where('id', $gid)->value('mass_startat');
        // 集结结束时间
        $res['massEndTime'] =  $res['massStartTime'] + $massCfg['start'];
        // 冷却结束时间
        $res['coolEndTime'] = $res['massEndTime'] + $massCfg['cool'];

        if (time() >= $res['coolEndTime']) {
            // 可集结
            $res['status'] = 0;
            $res['remainTime'] = 0;
        } else if (time() >= $res['massEndTime']) {
            // 集结冷却中
            $res['status'] = 1;
            $res['remainTime'] = $res['coolEndTime'] - time();
        } else if (time() >= $res['massStartTime']) {
            // 正在集结
            $res['status'] = 2;
            $res['remainTime'] = $res['massEndTime'] - time();
        }

        return $res;
    }

    /**参加集结 */
    public static function massJoin($gid, $force, $uid)
    {
        $res = self::massStatus($gid);

        if ($res['status'] == 2) {
            // 正在集结，避免同一用户重复参与
            $isJoin = self::where('user_id', $uid)->where('wxgroup_id', $gid)
                ->whereTime('mass_join_at', 'between', [$res['massStartTime'], $res['massEndTime']])->find();
            if ($isJoin) Common::res(['code' => 1, 'msg' => '你已参加本次群集结']);
        } else if ($res['status'] == 1) {
            // 正在冷却中
            Common::res(['code' => 1, 'msg' => '正在冷却中']);
        }
        // 每天最多参加20次群集结
        $dayMassTimes = UserExt::where('user_id', $uid)->value('group_mass_times');
        if ($dayMassTimes >= Cfg::getCfg('groupmass')['day_max_times']) Common::res(['code' => 1, 'msg' => '今日已参加了20次了']);

        Db::startTrans();
        try {
            $startTime = time();
            self::where('user_id', $uid)->where('wxgroup_id', $gid)->update([
                'mass_join_at' => $startTime,
                'mass_point' => $force,
                'mass_times' => Db::raw('mass_times+1')
            ]);

            if ($res['status'] == 0) {
                // 集结开始，记录开始时间
                Wxgroup::where('id', $gid)->update(['mass_startat' => $startTime, 'mass_settle' => 1]);
                // 记录一条群集结动态
                WxgroupDynamic::create(['user_name' => UserModel::where('id', $uid)->value('nickname'), 'text' => '刚刚发起了集结']);
            }
            // 今日参与群集结次数加一
            UserExt::where('user_id', $uid)->update(['group_mass_times' => Db::raw('group_mass_times+1')]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**群集结结算 */
    public static function massSettle()
    {
        // 查找所有集结未结算的群
        $groupList = Wxgroup::where('mass_settle', 1)->select();
        foreach ($groupList as $group) {
            $status = self::massStatus($group['id']);
            if ($status['status'] != 2) {
                // 不对正在集结的群进行结算
                // 参加了本次群集结的用户
                $massList = self::where('wxgroup_id', $group['id'])
                    ->whereTime('mass_join_at', 'between', [$status['massStartTime'], $status['massEndTime']])->select();

                Db::startTrans();
                try {
                    // 该群已结算
                    $isDone = Wxgroup::where('id', $group['id'])->update(['mass_settle' => 0]);
                    if (!$isDone) {
                        Db::rollback();
                        continue;
                    }
                    // 参与人数需至少3人
                    if (count($massList) >= 3) {
                        // 集结奖励分发到每个参与用户
                        $userService = new UserService();
                        foreach ($massList as $user) {
                            $userService->change($user['user_id'], [
                                'coin' => $user['mass_point']
                            ], ['type' => 28]);
                        }
                    };

                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    Common::res(['code' => 400, 'msg' => $e->getMessage()]);
                }
            }
        }
    }

    /**群贡献奖励 */
    public static function groupDayReback($uid)
    {
        $reback = self::where('user_id', $uid)->sum('daycount_reback');

        Db::startTrans();
        try {
            (new UserService)->change($uid, [
                'coin' => $reback
            ], ['type' => 29]);

            self::where('user_id', $uid)->update(['daycount_reback' => 0]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $reback;
    }
}
