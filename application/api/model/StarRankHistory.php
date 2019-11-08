<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class StarRankHistory extends Base
{
    //

    public function getValueAttr($value)
    {
        return json_decode($value, true);
    }
}
