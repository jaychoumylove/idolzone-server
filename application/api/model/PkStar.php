<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class PkStar extends Base
{
    /**新增团战明星 */
    public static function newStar($starid, $pkType, $pkTime)
    {
        $starExist = self::where(['pk_time' => $pkTime, 'pk_type' => $pkType, 'star_id' => $starid])->value('id');
        if (!$starExist) {
            self::create([
                'star_id' => $starid,
                'pk_time' => $pkTime,
                'pk_type' => $pkType,
            ]);
        }
    }
}
