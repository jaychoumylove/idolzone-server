<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;
use app\api\service\User as UserService;

class ShareMass extends Base
{

    public function User()
    {
        return $this->belongsTo('User')->field('id,nickname,avatarurl');
    }

    public static function getMass($uid)
    {
        $item = self::get(['user_id' => $uid]);
        if (!$item) {
            self::create(['user_id' => $uid]);
            $item = self::get(['user_id' => $uid]);
        }
        if ($item['mass_times'] != 0 && date('Ymd', $item['mass_start_time']) != date('Ymd', time())) {
            // 集结次数次日清零
            self::where(['user_id' => $uid])->update(['mass_times' => 0]);
            $item['mass_times'] = 0;
        }

        $item['mass_user'] = [];
        // 集结状态
        $massConfig = Cfg::getCfg('share_mass');

        if (time() - $item['mass_settle_time'] < $massConfig['cooling']) {
            // 冷却中
            $item['status'] = 2;
            $item['lefttime'] = $massConfig['cooling'] - time() + $item['mass_settle_time'];
        } else if (time() - $item['mass_start_time'] < $massConfig['duration']) {
            // 正在集结
            $item['status'] = 1;
            $item['lefttime'] = $massConfig['duration'] - time() + $item['mass_start_time'];
        } else {
            // 可开始新的集结
            $item['status'] = 0;
            $item['lefttime'] = null;
        }

        if ($item['mass_start_time'] > $item['mass_settle_time']) {
            $item['mass_user'] = RecMass::with('User')->where(['mass_uid' => $uid])
                ->whereTime('create_time', '>', $item['mass_start_time'])->select();
        }
        return $item;
    }

    public static function start($uid)
    {
        $mass = self::getMass($uid);
        if ($mass['status'] == 0) {
            if ($mass['mass_times'] < Cfg::getCfg('share_mass')['day_limit']) {
                self::where(['user_id' => $uid])->update([
                    'mass_start_time' => time(),
                    'mass_times' => Db::raw('mass_times+1')
                ]);
            } else {
                Common::res(['code' => 1, 'msg' => '今日集结次数已达上限']);
            }
        }
    }

    /**加入集结 */
    public static function join($rer, $uid)
    {
        if ($rer == $uid) return;
        $mass = self::getMass($rer);

        if ($mass['status'] == 1) {
            // 正在集结
            // 每天最多助力集结3次
            $dayLimitTimes = 3;
            $todayTimes = RecMass::where(['be_mass_uid' => $uid])->whereTime('create_time', 'd')->count();
            $leastTime = RecMass::where(['be_mass_uid' => $uid])->whereTime('create_time', 'd')->order('create_time desc')->value('create_time');
            if ($todayTimes < $dayLimitTimes && time() - strtotime($leastTime) > 3600) {
                RecMass::create(['be_mass_uid' => $uid, 'mass_uid' => $rer]);

                // 望帮别人集结成功也有100金豆奖励
                (new UserService())->change($uid, [
                    'coin' => Cfg::getCfg('share_mass')['earn']
                ], ['type' => 14]);

                return User::where(['id' => $rer])->value('nickname');
            }
        }
    }

    /**集结结算 */
    public static function settle($uid)
    {
        $mass = self::getMass($uid);
        if ($mass['mass_settle_time'] > $mass['mass_start_time']) Common::res(['code' => 1, 'msg' => '请勿重复结算']);
        // 集结人数
        $peopleCount = RecMass::with('User')->where(['mass_uid' => $uid])->whereTime('create_time', '>', $mass['mass_start_time'])->count();
        $earn = Cfg::getCfg('share_mass')['earn'] * $peopleCount;
        Db::startTrans();
        try {
            self::where(['user_id' => $uid])->update(['mass_settle_time' => time()]);

            (new UserService())->change($uid, [
                'coin' => $earn
            ], [
                'type' => 6
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $earn;
    }
}
