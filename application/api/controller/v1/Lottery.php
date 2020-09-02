<?php

namespace app\api\controller\v1;

use app\api\model\Cfg;
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
            $remainCount = UserExt::addCount($this->uid, $type);
        } else if ($type == 1) {
            // 在线
            $remainCount = UserExt::addCount($this->uid, $type);
        } else if ($type == 2) {
            $config = Cfg::getCfg(Cfg::FREE_LOTTERY);
            if (empty($config['enable_video_add']['status'])) {
                Common::res(['code' => 1, 'msg' => '暂未开放']);
            }
            // 观看视频 直接+5次
            $info = UserExt::where('user_id', $this->uid)->find();
            $currentTime = time();
            $timeDiff = (int)bcsub($currentTime, $info['lottery_time']);
            if ($timeDiff < $config['enable_video_add']['limit']) {
                Common::res(['code' => 1, 'msg' => sprintf('请%s秒后再试', $config['enable_video_add']['limit'])]);
            }
            $remainCount = (int)bcadd($info['lottery_count'], $config['enable_video_add']['number']);

            $leftTimes = bcsub($config['day_max'], $info['lottery_times']);
            if ($remainCount > $config['add_max']) $remainCount = $config['add_max'];

            $remainCount = $remainCount > $leftTimes ? $leftTimes: $remainCount;
            UserExt::where('user_id', $this->uid)->update(['lottery_count' => $remainCount, 'lottery_time' => $currentTime]);
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

    public function multipleStart()
    {
        $this->getUser();
        $type = input('type', 'hundred');

        $data = UserExt::multipleLottery($type, $this->uid);

        Common::res(compact('data'));
    }

    /*每日抽奖所得*/
    public function dayEarn()
    {
        $this->getUser();

        $res['coin'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('coin');
        $res['flower'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('flower');
        $res['stone'] = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->sum('stone');
        $res['times'] = UserExt::where('user_id', $this->uid)->value('lottery_times');
        Common::res(['data' => $res]);
    }

    /*每日抽奖日志*/
    public function log()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 15);

        $logList = RecLottery::where('user_id', $this->uid)->whereTime('create_time', 'd')->order('id desc')->page($page, $size)->select();

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
