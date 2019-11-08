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
        $week = date('w');
        if ($week == 5|| $week == 6|| $week == 0) {
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
