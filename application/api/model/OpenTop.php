<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class OpenTop extends Base
{
    //

    public function getUserRankAttr($value)
    {
        return json_decode($value, true);
    }
}
