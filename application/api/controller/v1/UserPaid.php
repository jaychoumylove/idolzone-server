<?php


namespace app\api\controller\v1;


use app\api\model\CfgPaid;
use app\api\model\RecUserPaid;
use app\api\model\RecUserPaidLog;
use app\base\service\Common;

class UserPaid extends \app\base\controller\Base
{
    public function settle()
    {
        $this->getUser ();

        $paid = input ('paid', false);
        if (false == $paid) {
            Common::res (['code' => 1, 'msg' => '请选择领取项']);
        }

        $paidInfo = CfgPaid::get ($paid);
        if (empty($paidInfo)) {
            Common::res (['code' => 1, 'msg' => '充值领取已下架']);
        }
        if ($paidInfo['status'] == CfgPaid::OFF) {
            Common::res (['code' => 1, 'msg' => '充值领取已下架']);
        }

        $earn = (new RecUserPaid())->settle ($paid, $this->uid);

        Common::res (['data' => $earn]);
    }

    public function setRecharge()
    {
        $this->getUser ();

        $number = input ('number');

        RecUserPaid::setTask ($this->uid, (int)$number);

        Common::res ();
    }

    public function getPaidInfo()
    {
        $this->getUser ();

        $sumMap  = [
            'type'   => CfgPaid::SUM,
            'status' => CfgPaid::ON
        ];
        $dayMap = $sumMap;
        $dayMap['type'] = CfgPaid::DAY;
        $dayPaid = CfgPaid::get ($dayMap); // 每日充值
        $sumPaid = CfgPaid::where ($sumMap)->order ('count', 'asc')->select (); // 累计充值

        $currentTime = date ('Y-m-d') . ' 00:00:00';

        $hasDayLogMap = [
            'user_id' => $this->uid,
            'paid_type' => CfgPaid::DAY,
        ];
        $hasDayLog = RecUserPaidLog::where($hasDayLogMap)
            ->where('create_time', '>=', $currentTime)
            ->find ();

        $isDayDouble = empty($hasDayLog);

        $hasSumLogMap = $hasDayLogMap;
        $hasSumLogMap['paid_type'] = CfgPaid::SUM;
        $hasSumLog = RecUserPaidLog::where($hasSumLogMap)
            ->where('create_time', '>=', $currentTime)
            ->find ();

        $isSumDouble = empty($hasSumLog);

        $myPaid = RecUserPaid::where ('user_id', $this->uid)->select ();
        if (is_object ($myPaid)) $myPaid = $myPaid->toArray ();

        $myDayPaid = [
            "user_id"   => $this->uid,
            'paid_type' => CfgPaid::DAY,
            'is_settle' => 0,
            'count'     => 0
        ];

        $mySumPaid              = $myDayPaid;
        $mySumPaid['paid_type'] = CfgPaid::SUM;
        foreach ($myPaid as $item) {
            if ($item['paid_type'] == CfgPaid::DAY) {
                $myDayPaid = $item;
            }

            if ($item['paid_type'] == CfgPaid::SUM) {
                $mySumPaid = $item;
            }
        }

        $dayPaid['settle_status'] = 0;
        if (empty($myDayPaid['is_settle'])) {
            if ($myDayPaid['count'] > $dayPaid['count']) {
                $dayPaid['settle_status'] = 1;
            }
        }

        foreach ($sumPaid as $index => $item) {
            $item['settle_status'] = 0;
            if ($mySumPaid['count'] > $item['count']) {
                $item['settle_status'] = 1;
            }

            $sumPaid[$index] = $item;
        }


        Common::res (['data' => compact ('sumPaid',
            'dayPaid',
            'myDayPaid',
            'mySumPaid',
            'isDayDouble',
            'isSumDouble')]);
    }

    public function getPaidLogPager()
    {
        $this->getUser ();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);


        $list = RecUserPaidLog::with('user')
            ->where('user_id', $this->uid)
            ->order ([
                'create_time' => 'desc',
                'id' => 'desc'
            ])
            ->page($page, $size)
            ->select();

        Common::res (['data' => $list]);
    }
}