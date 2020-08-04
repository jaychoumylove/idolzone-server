<?php


namespace app\api\controller\v1;


use app\api\model\CfgLuckyDraw;
use app\api\model\CfgScrap;
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

        $list = RecLuckyDrawLog::where('user_id', $this->uid)
            ->order ([
                'create_time' => "desc",
                'id' => 'desc'
            ])
            ->page ($page, $size)
            ->select ();
        if (is_object ($list)) $list = $list->toArray ();

        $data = [];
        foreach ($list as $key => $value)
        {
            $item = $value['item'];
            if ($item['type'] == CfgLuckyDraw::SCRAP)
            {
                $item['image'] = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0';
            }
            if ($item['type'] == RecLuckyDrawLog::SCRAP_L)
            {
                $scrap = CfgScrap::get ($value['item']['key']);
                $item['image'] = $scrap['image_l'];
            }

            $currencyMap = [
                'coin' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0',
                'flower' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0',
                'stone' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0',
                'trumpet' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk90Xa3b43zE46M8IkUvFyMR5GgfJN52icBqoicfKWfAJS8QXog0PZtgdEQ/0',
            ];
            if (array_key_exists ($item['key'], $currencyMap)) {
                $item['image'] = $currencyMap[$item['key']];
            }

            $value['item'] = $item;
            array_push ($data, $value);
        }


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
            Common::res (['code' => 1, 'msg' => '请选择兑换奖品']);
        }

        $key = UserScrap::exchange ($this->uid, $scrap);

        $msg = '兑换成功';
//        if (strpos ($key, 'open')) {
//            $msg = '兑换成功,请联系客服';
//        }

        Common::res (compact ('msg'));
    }
}