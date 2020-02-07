<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class FanclubBoxUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**打开宝箱 */
    public static function openBox($uid, $box_id)
    {
        $isExist = self::where('box_id', $box_id)->where('user_id', $uid)->value('id');
        if ($isExist) return;

        // 宝箱信息
        $boxInfo = FanclubBox::where('id', $box_id)->find();

        // 红包总个数
        $totalCount = $boxInfo['people'];
        $count = self::where('box_id', $box_id)->count('id');
        // 剩余个数
        $remainCount = $totalCount - $count;
        if ($remainCount <= 0) return;

        // 总奖金
        $totalSum = $boxInfo['coin'];
        $sum = self::where('box_id', $box_id)->sum('count');
        // 剩余奖金
        $remainSum = $totalSum - $sum;
        if ($remainSum <= 0) return;
        // 给予奖励数额
        $awardNum = self::getAward($remainSum, $remainCount);

        Db::startTrans();
        try {
            self::create([
                'box_id' => $box_id,
                'user_id' => $uid,
                'count' => $awardNum
            ]);

            (new UserService)->change($uid, ['coin' => $awardNum], '粉丝团宝箱奖励');

            RecTaskfather::addRec($uid, [8, 19, 30, 41]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 获取一个红包的奖金金额
     * @param int $total 奖金
     * @param int $count 剩余个数
     */
    public static function getAward($total, $count)
    {
        if ($count == 1) {
            // 最后一个红包，奖金全部给TA
            $award = $total;
        } else {
            // 奖金额度 = 奖金 / 红包个数 * 随机0.50-1.49倍
            do {
                $award = round($total / $count * mt_rand(50, 149) / 100);
            } while ($award >= $total);
        }

        return $award;
    }
}
