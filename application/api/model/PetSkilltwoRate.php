<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class PetSkilltwoRate extends Base
{
    /**暴击概率 */
    public static function getRate($uid)
    {
        // 基础的概率
        $baseChance = [
            ['rate' => 1, 'chance' => 55], // 最低的rate
            ['rate' => 3, 'stone' => 10, 'chance' => 33],
            ['rate' => 5, 'stone' => 10, 'chance' => 8],
            ['rate' => 8, 'stone' => 10, 'chance' => 3],
            ['rate' => 10, 'stone' => 10, 'chance' => 1],
        ];
        // 增加的概率
        // $myRate = self::where('user_id', $uid)->find();
        // if ($myRate) {
        //     foreach ($baseChance as &$value) {
        //         $increaseRate = $myRate['p_' . $value['rate']];
        //         if ($increaseRate) {
        //             // 增加概率+1
        //             $value['chance'] += $increaseRate;
        //             $value['change'] = 'in';
        //             // 在1倍的概率-1
        //             $baseChance[0]['chance'] -= $increaseRate;
        //             $baseChance[0]['change'] = 'de';
        //             // 下一次增加概率需要的钻石
        //             $value['stone'] += $increaseRate * 10;
        //         }
        //     }
        // }

        return $baseChance;
    }

    /**增加概率 */
    public static function increaseRate($uid, $rate, $stone)
    {
        $maxTimes = 4;
        $myRate = self::where('user_id', $uid)->find();
        if ($myRate['p_' . $rate] >= $maxTimes) Common::res(['code' => 1, 'msg' => '此项概率无法再提升']);

        Db::startTrans();
        try {
            (new User)->change($uid, ['stone' => -$stone], '概率提升');

            $isDone = self::where('user_id', $uid)->update([
                'p_' . $rate => Db::raw('p_' . $rate . '+1')
            ]);
            if (!$isDone) {
                self::create([
                    'user_id' => $uid,
                    'p_' . $rate => 1
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
