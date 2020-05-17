<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class LotteryBox extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**获取宝箱收益 */
    public static function openBox($uid, $rec_lottery_id)
    {
        $isExist = self::where('rec_lottery_id', $rec_lottery_id)->where('user_id', $uid)->value('id');
        if ($isExist) return;

        // 红包总个数
        $totalCount = 10;
        $count = self::where('rec_lottery_id', $rec_lottery_id)->count('id');
        // 剩余个数
        $remainCount = $totalCount - $count;
        if ($remainCount <= 0) return;

        $recLotteryBox = RecLotteryBox::with(['lottery'])->where('id', $rec_lottery_id)->find();
        // 总奖金
        $totalSum = $recLotteryBox['lottery']['num'];
        $sum = self::where('rec_lottery_id', $rec_lottery_id)->sum('earn');
        // 剩余奖金
        $remainSum = $totalSum - $sum;
        if ($remainSum <= 0) return;
        // 给予奖励数额
        $awardNum = self::getAward($remainSum, $remainCount);
        $recLotteryBox['lottery']['num'] = $awardNum;

        Db::startTrans();
        try {
            self::create([
                'rec_lottery_id' => $rec_lottery_id,
                'user_id' => $uid,
                'earn' => $awardNum
            ]);

            UserExt::grant($uid, $recLotteryBox['lottery']);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 获取一个红包的奖金金额
     * @param int $total 奖金
     * @param int $count 个数
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
