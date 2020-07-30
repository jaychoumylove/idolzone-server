<?php


namespace app\api\controller\v1;


use app\api\model\CfgPaid;
use app\api\model\RecUserPaid;
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

        RecUserPaid::setTask ($this->uid, $number);

        Common::res ();
    }

    public function getPaidInfo()
    {
        $this->getUser ();

        $dayPaid = CfgPaid::get (['type' => CfgPaid::DAY]); // 每日充值

        $sumMap  = [
            'type'   => CfgPaid::SUM,
            'status' => CfgPaid::ON
        ];
        $sumPaid = CfgPaid::where ($sumMap)->order ('count', 'asc')->select (); // 累计充值

        $myPaid = RecUserPaid::where ('user_id', $this->uid)->select ();
        if (is_object ($myPaid)) $myPaid = $myPaid->toArray ();

        $myDayPaid = null;
        foreach ($myPaid as $item) {
            if ($item['paid_type'] == CfgPaid::DAY) {
                $myDayPaid = $item;
            }

            if ($item['paid_type'] == CfgPaid::SUM) {
                $mySumPaid = $item;
            }
        }

        $dayPaid['settle_status'] = 0;
        if (isset($myDayPaid)) {
            if (empty($myDayPaid['is_settle'])) {
                if ($myDayPaid['count'] > $dayPaid['count']) {
                    $dayPaid['settle_status'] = 1;
                }
            }
        }

        if (isset($mySumPaid)) {
            foreach ($sumPaid as $index => $item) {
                $item['settle_status'] = 0;
                if ($mySumPaid['count'] > $item['count']) {
                    $item['settle_status'] = 1;
                }

                $sumPaid[$index] = $item;
            }
        }


        Common::res (['data' => compact ('sumPaid', 'dayPaid', 'myDayPaid', 'mySumPaid')]);
    }
}