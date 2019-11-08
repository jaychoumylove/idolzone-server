<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\UserStar;
use app\base\service\Common;

class SubScribe  extends Base
{
    public function index()
    {
        $this->getUser();
        $sub_type = input('sub_type'); // 订阅类型
        $flag = input('flag'); // 订阅/取消订阅

        switch ($sub_type) {
            case 'active_card':
                // 活动订阅
                UserStar::where('user_id', $this->uid)->update(['active_subscribe' => $flag]);
                break;
            default:
                # code...
                break;
        }

        Common::res(['data' => $flag]);
    }
}
