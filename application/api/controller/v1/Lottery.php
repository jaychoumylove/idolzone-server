<?php

namespace app\api\controller\v1;

use app\api\model\RecLotteryBox;
use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserExt;
use app\api\service\User;
use app\api\model\LotteryBox;
use app\api\model\Rec;
use app\api\model\RecLottery;

class Lottery extends Base
{
    /*抽奖-自动回血*/
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
            if ($remainCount > 30) $remainCount = 30;
            UserExt::where('user_id', $this->uid)->update(['lottery_count' => $remainCount, 'lottery_time' => time()]);
        }

        Common::res(['data' => $remainCount]);
    }

    /*抽奖*/
    public function start()
    {
        $this->getUser();
        $res = UserExt::lotteryStart($this->uid);
        Common::res(['data' => $res]);
    }

    /*每日抽奖所得*/
    public function dayEarn()
    {
        $this->getUser();

//        $res['coin'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('coin');
//        $res['flower'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('flower');
//        $res['stone'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('stone');

        $res['coin'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('coin');
        $res['flower'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('flower');
        $res['stone'] = Rec::where('content', '幸运抽奖')->where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('stone');
        $res['times'] = UserExt::where('user_id', $this->uid)->value('lottery_times');
        Common::res(['data' => $res]);
    }

    /*每日抽奖日志*/
    public function log()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

//        $logList = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->order('id desc')->page($page, $size)->select();
        $logList = Rec::where('user_id', $this->uid)->where('content', '幸运抽奖')->whereTime('create_time', 'd')->order('id desc')->page($page, $size)->select();

        Common::res(['data' => $logList]);
    }

    /**双倍领取 */
    public function double()
    {
        // $this->getUser();
        // $latest = Rec::where('user_id', $this->uid)->where('content', '幸运抽奖')->order('id desc')->find();

        // (new User)->change($this->uid, [
        //     'coin' => $latest['coin'],
        //     'flower' => $latest['flower'],
        //     'stone' => $latest['stone'],
        //     'trumpet' => $latest['trumpet'],
        // ]);
        // // 更新日志
        // Rec::where('id', $latest['id'])->update([
        //     'coin' => $latest['coin'] * 2,
        //     'flower' => $latest['flower'] * 2,
        //     'stone' => $latest['stone'] * 2,
        //     'trumpet' => $latest['trumpet'] * 2,
        // ]);
        // Common::res();
    }

    /*宝箱信息*/
    public function getBox()
    {
        $rec_lottery_id = $this->req('rec_lottery_id', 'integer');

        $data = RecLotteryBox::with(['user'])->where('id', $rec_lottery_id)->find();
        Common::res(['data' => $data]);
    }

    /*开宝箱*/
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
}
