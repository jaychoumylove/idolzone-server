<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class CfgLottery extends Base
{
    /**周末抽奖双倍 */
    public static function doubleAward()
    {
        // 567
        $status = Cfg::checkConfigTime(Cfg::MANOR_NATIONAL_DAY);
        if ($status) {
            self::where('1=1')->update([
                'num' => Db::raw('weekend')
            ]);
        } else {
            $week = date('w');
            $double = [5,6,0];
            if (in_array($week, $double)) {
                self::where('1=1')->update([
                    'num' => Db::raw('weekend')
                ]);
            } else {
                self::where('1=1')->update([
                    'num' => Db::raw('normal')
                ]);
            }
        }
    }
}
