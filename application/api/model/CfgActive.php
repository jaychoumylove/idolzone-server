<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Model;

class CfgActive extends Base
{
    /**检查是否在活动时间内 */
    public static function checkInDate($active_id)
    {
        $activeDate = self::where('id', $active_id)->value('active_date');

        $dateArr = json_decode($activeDate, true);
        $startTime = strtotime($dateArr[0]);
        $endTime = strtotime($dateArr[1]);

        if (time() < $startTime) {
            Common::res(['code' => 1, 'msg' => '活动还未开始']);
        } else if (time() > $endTime) {
            Common::res(['code' => 1, 'msg' => '活动已经结束']);
        }
    }

    public static function getSameCategoryIds($active_id)
    {
        $active_cid = self::where('id', $active_id)->value('category');
        return self::where('category', $active_cid)->column('id');
    }
}
