<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserExt;
use app\api\service\User;
use app\api\model\Cfg;
use think\Db;

class Lottery extends Base
{

    public function index()
    {
        $this->getUser();
        $info = UserExt::getTreasure($this->uid);
        Common::res(['data' => $info]);
    }

    public function start()
    {
        $this->getUser();

        $res = UserExt::lotteryStart($this->uid);

        Common::res(['data' => $res]);
    }

    public function settle()
    {
        $this->getUser();
        $time = UserExt::getTreasure($this->uid);
        if ($time != 0) Common::res(['code' => 1]);

        Db::startTrans();
        try {
            UserExt::where(['user_id' => $this->uid])->update([
                'treasure_time' => time()
            ]);

            (new User)->change($this->uid, [
                'coin' => Cfg::getCfg('treasure_earn'),
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }

        Common::res(['data' => Cfg::getCfg('treasure_earn')]);
    }
}
