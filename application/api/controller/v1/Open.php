<?php

namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\GuideCron;
use app\api\model\Open as OpenModel;
use app\api\model\OpenRank;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class Open extends Base
{
    public function upload()
    {
        $imgUrl = $this->req ('img_url', 'require');
        $type   = input ('type', OpenModel::NORMAL);
        $this->checkType ($type);
        $this->getUser ();
        $starId = UserStar::getStarId ($this->uid);
        if (!$starId) Common::res (['code' => 1, 'msg' => '请先加入一个圈子']);

        $todayDone = OpenModel::where ('user_id', $this->uid)
            ->whereTime ('create_time', 'd')
            ->value ('id');
        if ($todayDone) Common::res (['code' => 1, 'msg' => '每日只可上传一张']);

        $data = [
            'type'    => $type,
            'user_id' => $this->uid,
            'star_id' => $starId,
            'img_url' => $imgUrl
        ];
        OpenModel::create ($data);

        Common::res ();
    }

    protected function checkType($type)
    {
        if ($type == OpenModel::SOLDIER81) {
            $status = OpenModel::checkSoldier81 ();
            if (empty($status)) {
                Common::res (['msg' => '活动已结束']);
            }
        }
    }

    public function sendHot()
    {
        $id  = $this->req ('id', 'integer', 1);
        $hot = $this->req ('hot', 'integer', 1);
        if (empty($hot)) {
            Common::res (['code' => 1, 'msg' => '请选择赠送鲜花数目']);
        }
        $type = input ('type', OpenModel::NORMAL);
        $this->checkType ($type);
        $this->getUser ();
        $starId = UserStar::getStarId ($this->uid);
        if (!$starId) Common::res (['code' => 1, 'msg' => '请先加入一个圈子']);

        $open = OpenModel::get ($id);
        if (empty($open)) {
            Common::res (['code' => 1, 'msg' => '请选择开屏图']);
        }

        if ($open['star_id'] != $starId) {
            Common::res (['code' => 1, 'msg' => '不能给其他的爱豆助力哦']);
        }

        Db::startTrans ();
        try {
            OpenRank::assist ($id, $this->uid, $hot);
            (new \app\api\service\User())->change ($this->uid, ['flower' => -$hot], '【最美军装】开屏鲜花助力');
            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
            Common::res (['code' => 1, 'msg' => "助力失败，请稍后再试"]);
        }

        Common::res (['msg' => "助力成功"]);
    }

    public function userRank()
    {
        $open = $this->req ('open_id', 'integer', 0);
        $page = $this->req ('page', 'integer', 1);
        $size = $this->req ('size', 'integer', 10);
        $type = input ('type', OpenModel::NORMAL);
        $this->getUser ();
        if (empty($open)) {
            Common::res (['code' => 1, 'msg' => '请选择开屏图']);
        }

        $map = [
            'type' => $type,
            'open_id' => $open
        ];
        $list = OpenRank::getPager ($map, $page, $size);

        Common::res (['data' => $list]);
    }

    public function select()
    {
        $page = $this->req ('page', 'integer', 1);
        $size = $this->req ('size', 'integer', 10);
        $type = input ('type', OpenModel::NORMAL);
        $this->getUser ();
        $this->checkType ($type);
        $map = compact ('type');
        // 列表
        $res['list'] = OpenModel::getRankList ($map, $page, $size, 0);

        Common::res (['data' => $res]);
    }

    public function settle()
    {
        OpenModel::settle ();
    }

    public function today()
    {
        $img = GuideCron::where ('start_time', '<', time ())->where ('end_time', '>', time ())->value ('open_img');
        if (!$img) {
            $img = Cfg::getCfg ('open_img');
        }
        Common::res (['data' => $img]);
    }
}
