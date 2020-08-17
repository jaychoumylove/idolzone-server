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
        $settle = input ('settle', false);
        if (empty($settle)) {
            Common::res (['code' => 1, 'msg' => "暂未开放"]);
        }
        $this->getUser ();

        $res = \app\api\model\UserInvite::settleInvite ($this->uid, $settle);
        if (is_string ($res)) {
            Common::res (['code' => 1, 'msg' => $res]);
        }

        Common::res (['data' => $res]);
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

    public function rank()
    {
        $this->getUser ();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $starId = UserStar::getStarId ($this->uid);
        $list = \app\api\model\UserInvite::with(['user'])
            ->where('star_id', $starId)
            ->order ([
                'invite_sum' => 'desc',
                'create_time' => 'asc',
            ])
            ->page ($page, $size)
            ->select ();

        Common::res (['data' => $list]);
    }
}