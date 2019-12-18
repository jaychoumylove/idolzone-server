<?php

namespace app\api\controller;

use think\Controller;
use app\base\service\Common;
use app\base\controller\Base;

class Test extends Base
{

    public function getToken()
    {
        echo Common::setSession(input('uid') / 1234);
    }

    public function getUid()
    {
        echo Common::getSession(input('token'));
    }
}
