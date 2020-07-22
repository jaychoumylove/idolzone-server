<?php

namespace app\api\model;

use app\base\model\Base;

class Star extends Base
{
    /**
     * 获得用户idol信息
     * @param $uid
     * @return Star|bool
     * @throws \think\exception\DbException
     */
    public static function getByUser($uid)
    {
        $userStar = UserStar::get (['user_id' => $uid]);
        if (empty($userStar)) return false;

        $star = self::get ($userStar['star_id']);
        if (empty($star)) return false;

        return $star;
    }


    public function StarRank()
    {
        return $this->hasOne('StarRank', 'star_id', 'id')->field('id', true);
    }

    /**距离上一个明星差距数额 */
    public static function disLeastCount($star_id, $field = 'week_hot')
    {
        $selfCount = StarRank::where('star_id', $star_id)->value($field);
        $leastCount = StarRank::where($field, '>', $selfCount)->value($field);

        if ($leastCount) {
            return $leastCount - $selfCount;
        } else {
            return 0;
        }
    }
}
