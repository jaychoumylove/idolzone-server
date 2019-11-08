<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserExt;
use app\api\service\User;
use app\api\model\Cfg;
use app\api\model\LotteryBox;
use app\api\model\RecLottery;
use think\Db;

class Lottery extends Base
{
    public function addCount()
    {
        $this->getUser();
        $type = $this->req('type', 'integer', 0);

        if ($type == 0) {
            // 离线
            $max = 10;
            $remainCount = UserExt::addCount($this->uid, $max);
        } else if ($type == 1) {
            // 在线
            $max = 30;
            $remainCount = UserExt::addCount($this->uid, $max);
        } else if ($type == 2) {
            // 观看视频 直接+5次
            $remainCount = UserExt::where('user_id', $this->uid)->value('lottery_count');
            $remainCount += 5;
            UserExt::where('user_id', $this->uid)->update(['lottery_count' => $remainCount, 'lottery_time' => time()]);
        }

        Common::res(['data' => $remainCount]);
    }

    public function start()
    {
        $this->getUser();
        $res = UserExt::lotteryStart($this->uid);
        Common::res(['data' => $res]);
    }

    public function getBox()
    {
        $rec_lottery_id = $this->req('rec_lottery_id', 'integer');

        $data = RecLottery::with(['user', 'lottery'])->where('id', $rec_lottery_id)->find();
        Common::res(['data' => $data]);
    }

    public function getBoxOpen()
    {
        $rec_lottery_id = $this->req('rec_lottery_id', 'integer');
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $this->getUser();
        LotteryBox::openBox($this->uid, $rec_lottery_id);

        $res['self'] = LotteryBox::with('user')->where('rec_lottery_id', $rec_lottery_id)->where('user_id', $this->uid)->find();
        $res['list'] = LotteryBox::with('user')->where('rec_lottery_id', $rec_lottery_id)->page($page, $size)->select();
        // 手气最佳
        $res['lucky_boy'] = LotteryBox::where('rec_lottery_id', $rec_lottery_id)->order('earn desc')->value('user_id');
        // 奖品type 1coin
        $res['award_type'] = RecLottery::with(['lottery'])->where('id', $rec_lottery_id)->find()['lottery']['type'];

        Common::res(['data' => $res]);
    }

    public function dayEarn()
    {
        $this->getUser();

        $res['coin'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('coin');
        $res['flower'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('flower');
        $res['stone'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('stone');

        Common::res(['data' => $res]);
    }
}
