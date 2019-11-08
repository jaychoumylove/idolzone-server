<?php

namespace app\api\model;

use app\base\model\Base;

class CfgUserLevel extends Base
{
    /**获取用户的粉丝等级 */
    public static function getLevel($uid)
    {
        $count = UserStar::where('user_id', $uid)->order('id desc')->value('total_count');
        return self::where('total', '<=', $count)->max('level');
    }
}
