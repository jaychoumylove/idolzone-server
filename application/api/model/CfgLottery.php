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
        $double = [5,6,0];
        $type = in_array($week, $double) ? 'weekend': 'normal';
        self::where('1=1')->update(['num' => Db::raw($type)]);
    }
}
