<?php

namespace app\api\controller\v1;

use app\api\model\CfgHeadwear;
use app\api\model\HeadwearUser;
use app\base\controller\Base;
use app\base\service\Common;

class Headwear extends Base
{
    public function select()
    {
        $this->getUser();

        //删除过期的头饰
        HeadwearUser::where('uid', $this->uid)->where('end_time<"'.date('Y-m-d H:i:s').'"')->delete();
        
        $res = CfgHeadwear::getAll($this->uid);
        Common::res(['data' => $res]);
    }

    public function buy()
    {
        $this->getUser();

        $hid = input('hid');
        $res = HeadwearUser::buy($this->uid, $hid);
        Common::res(['data' => $res]);
    }

    public function use()
    {
        $this->getUser();

        $hid = input('hid');
        HeadwearUser::use($this->uid, $hid);
        Common::res();
    }
    public function cancel()
    {
        $this->getUser();

        HeadwearUser::cancel($this->uid);
        Common::res();
    }
}
