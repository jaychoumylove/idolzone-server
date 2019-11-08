<?php

namespace app\api\model;

use app\base\model\Base;

class Star extends Base
{
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
