<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class Prop extends Base
{
    const STORE  = 'STORE'; // 商店兑换
    const TASK   = 'TASK'; //  做任务获取
    const CHARGE = 'CHARGE'; // 充值获取

    const LUCKY_DRAW = 'lucky_draw';
}
