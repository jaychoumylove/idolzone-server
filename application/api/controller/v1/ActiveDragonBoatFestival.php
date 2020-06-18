<?php
namespace app\api\controller\v1;

use app\api\model\ActiveDragonBoatFestival as ActiveDragonBoatFestivalModel;
use app\base\controller\Base;
use app\base\service\Common;

class ActiveDragonBoatFestival extends Base
{

    /**端午活动列表*/
    public function index()
    {
        $this->getUser();

        $res['list'] = (new ActiveDragonBoatFestivalModel())->getList();

        Common::res(['data' => $res]);
    }

    /**端午活动粉丝团列表*/
    public function funclubList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);


        $res = [];

        Common::res(['data' => $res]);
    }

    /**端午活动粉丝团用户列表*/
    public function funclubUserList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);

        $res = [];

        Common::res(['data' => $res]);
    }

}
