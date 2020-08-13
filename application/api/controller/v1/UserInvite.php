<?php


namespace app\api\controller\v1;


use app\api\model\RecUserInvite;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;

class UserInvite extends Base
{
    public function settle()
    {

    }

    public function recList()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $starId = UserStar::getStarId ($this->uid);

        $list = RecUserInvite::where('user_id', $this->uid)
            ->whereOr ('star_id', $starId)
            ->order ('create_time', 'desc')
            ->page ($page, $size)
            ->select ();

        Common::res (['data' => $list]);
    }
}