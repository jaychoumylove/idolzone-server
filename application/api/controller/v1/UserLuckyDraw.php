<?php


namespace app\api\controller\v1;


use app\api\model\CfgLuckyDraw;
use app\api\model\Prop;
use app\api\model\RecLuckyDrawLog;
use app\api\model\UserProp;
use app\api\model\UserScrap;
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

    public function dayEarn()
    {
        $this->getUser ();

        $date = date ('Y-m-d') . " 00:00:00";
        $list = RecLuckyDrawLog::where('user_id', $this->uid)
            ->where ('create_time', '>', $date)
            ->order ([
                'create_time' => "desc",
                'id' => 'desc'
            ])
            ->select ();

        if (is_object ($list)) $list = $list->toArray ();

        $data = [
            'coin' => 0,
            'flower' => 0,
            'stone' => 0,
            'trumpet' => 0,
        ];

        $items = array_column ($list, 'item');

        foreach ($items as $item) {
            if (array_key_exists ($item['key'], $data)) {
                $data[$item['key']] = bcadd ($data[$item['key']], $item['number']);
            }
        }

        Common::res (compact ('data'));
    }

    public function exchangeScrap()
    {
        $this->getUser ();

        $scrap = input ('scrap', false);

        if (empty($scrap)) {
            Common::res (['code' => 1, 'msg' => '请选择兑换碎片']);
        }

        UserScrap::exchange ($this->uid, $scrap);

        Common::res ();
    }
}