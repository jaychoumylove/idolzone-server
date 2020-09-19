<?php


namespace app\api\controller\v1;


use app\api\model\CfgLuckyDraw;
use app\api\model\CfgScrap;
use app\api\model\Prop;
use app\api\model\RecLuckyDrawLog;
use app\api\model\UserAnimal;
use app\api\model\UserProp;
use app\api\model\UserScrap;
use app\base\controller\Base;
use app\base\service\Common;

class UserLuckyDraw extends Base
{
    public function getLuckyDraw()
    {
        $this->getUser();

        $luckyDraw = CfgLuckyDraw::getLuckyDraw();

        $luckyDrawTrick = Prop::where('key', Prop::LUCKY_DRAW)->value('id');

        $luckyDrawTrickNum = UserProp::getNum($this->uid, $luckyDrawTrick);

        Common::res(['data' => [
            'lucky_draw' => $luckyDraw,
            'my_num'     => $luckyDrawTrickNum
        ]]);
    }

    public function startLuckyDraw()
    {
        $this->getUser();

        $data = CfgLuckyDraw::start($this->uid);

        Common::res(compact('data'));
    }

    public function startLuckyDrawFifty()
    {
        $this->getUser();

        $data = CfgLuckyDraw::startFifty($this->uid);

        Common::res(compact('data'));
    }

    public function logPager()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $data = RecLuckyDrawLog::getLogPager($this->uid, $page, $size);

        Common::res(['data' => $data]);
    }

    public function dayEarn()
    {
        $this->getUser();

        $date = date('Y-m-d') . " 00:00:00";
        $list = RecLuckyDrawLog::where('user_id', $this->uid)
            ->where('create_time', '>', $date)
            ->order([
                'create_time' => "desc",
                'id'          => 'desc'
            ])
            ->select();

        if (is_object($list)) $list = $list->toArray();

        $data = [
            'coin'    => 0,
            'flower'  => 0,
            'trumpet' => 0,
            'panacea' => 0,
            'scrap'   => 0,
        ];

        $items = array_column($list, 'item');

        foreach ($items as $item) {
            if (array_key_exists('number', $item)) {
                if (array_key_exists($item['key'], $data)) {
                    $data[$item['key']] = bcadd($data[$item['key']], (int)$item['number']);
                }
            } else {
                foreach ($item as $key => $value) {
                    if (array_key_exists($value['key'], $data)) {
                        $data[$value['key']] = bcadd($data[$value['key']], (int)$value['number']);
                    }
                }
            }
        }

        Common::res(compact('data'));
    }

    public function exchangeScrap()
    {
        $this->getUser();

        $scrap = input('scrap', false);

        if (empty($scrap)) {
            Common::res(['code' => 1, 'msg' => '请选择兑换奖品']);
        }

        $key = UserScrap::exchange($this->uid, $scrap);

        $msg = '兑换成功';
//        if (strpos ($key, 'open')) {
//            $msg = '兑换成功,请联系客服';
//        }

        Common::res(compact('msg'));
    }

    public function exchangeAnimal()
    {
        $this->getUser();

        $animal_id = input('animal_id', 0);
        if (empty($animal_id)) {
            Common::res(['code' => 1, 'msg' => '请选择兑换宠物']);
        }
        $type = input('type', false);
        if (empty($type)) {
            Common::res(['code' => 1, 'msg' => '请选择兑换方式']);
        }

        UserAnimal::exchangeByNational($this->uid, $type, $animal_id);

        Common::res();
    }
}