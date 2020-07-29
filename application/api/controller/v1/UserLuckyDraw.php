<?php


namespace app\api\controller\v1;


use app\api\model\CfgLuckyDraw;
use app\api\model\Prop;
use app\api\model\RecLuckyDrawLog;
use app\api\model\UserProp;
use app\base\service\Common;

class UserLuckyDraw extends \app\base\controller\Base
{
    public function getLuckyDraw()
    {
        $this->getUser ();

        $luckyDraw = CfgLuckyDraw::getLuckyDraw ();

        $luckyDrawTrick = Prop::where('key', Prop::LUCKY_DRAW)->value ('id');

        $luckyDrawTrickNum = UserProp::getNum ($this->uid, $luckyDrawTrick);

        Common::res (['data' => [
            'lucky_draw' => $luckyDraw,
            'my_num' => $luckyDrawTrickNum
        ]]);
    }

    public function startLuckyDraw()
    {
        $this->getUser ();

        $data = CfgLuckyDraw::start($this->uid);

        Common::res (compact ('data'));
    }

    public function logPager()
    {
        $this->getUser ();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $data = RecLuckyDrawLog::where('user_id', $this->uid)
            ->order ([
                'create_time' => "desc",
                'id' => 'desc'
            ])
            ->page ($page, $size)
            ->select ();

        Common::res (compact ('data'));
    }
}