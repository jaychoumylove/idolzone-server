<?php

namespace app\api\controller;

use app\api\model\Lock;
use app\api\model\StarBirthRank;
use think\Controller;
use app\base\service\Common;
use app\base\controller\Base;
use app\api\model\UserExt;
use app\api\model\User;
use app\api\model\UserStar;
use app\api\service\Redis;
use think\Db;

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

    public function index()
    {
    }
}
