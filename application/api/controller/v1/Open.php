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

        $userStar = UserStar::get (['user_id' => $this->uid]);
        if (empty($userStar)) {
            Common::res (['code' => 1, 'msg' => '请先加入一个圈子']);
        }

        if ((int)$userStar['captain'] != 1) {
            Common::res (['code' => 1, 'msg' => '只有领袖粉可以上传哦']);
        }

        $count = OpenModel::where ('user_id', $this->uid)->count ();
        if ($count >= 1) Common::res (['code' => 1, 'msg' => '每人只可以上传1张图片哦']);

        $data = [
            'type'    => $type,
            'user_id' => $this->uid,
            'star_id' => $userStar['star_id'],
            'img_url' => $imgUrl
        ];
        $info = OpenModel::create ($data);

        Common::res (['data' => ['id' => $info['id']]]);
    }

    protected function checkType($type)
    {
        if ($type == OpenModel::SOLDIER81) {
            $status = OpenModel::checkSoldier81 ();
            if (empty($status)) {
                Common::res (['msg' => '活动已结束', 'code' => 1]);
            }
        }
    }

    public function sendHot()
    {
        $id  = $this->req ('id', 'integer', 1);
        $hot = $this->req ('hot', 'integer', 1);
        $current = $this->req ('current', 'integer', 0);
        $danmaku = $this->req('danmaku', 'integer', 1); // 是否推送打榜弹幕
        if (empty($hot)) {
            Common::res (['code' => 1, 'msg' => '请选择赠送数目']);
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
            $sumHot = \app\api\service\Star::getExtraSendHotSum ($this->uid, $hot);
            OpenRank::assist ($id, $this->uid, $sumHot);
            $updated = OpenModel::where('id', $id)->where('hot', $open['hot'])->update([
                'hot' => bcadd ($open['hot'], $sumHot)
            ]);
            if (empty($updated)) {
                Common::res (['code' => 1, 'msg' => "助力失败,请稍后再试"]);
            }
            (new \app\api\service\Star())->sendHot ($starId, $hot, $this->uid, bcadd ($current, 1), (bool)$danmaku);
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
        $this->getUser ();
        if (empty($open)) {
            Common::res (['code' => 1, 'msg' => '请选择开屏图']);
        }

        $map = [
            'open_id' => $open
        ];

        $info = OpenModel::with(['Star', 'uploader'])->where('id', $open)->find ();
        if (is_object ($info)) $info = $info->toArray ();
        if (empty($info)) {
            Common::res (['data' => ['redirect' => true]]);
        }
        $info['rank'] = OpenModel::supportRank ($info['hot'], $info['id']);
        $list = OpenRank::getPager ($map, $page, $size);

        Common::res (['data' => compact ('info', 'list')]);
    }

    public function info()
    {
        $open = $this->req ('open_id', 'integer', 0);
        $map = ['id' => $open];
        $info = OpenModel::with('Star')->where($map)->order ('id', 'asc')->find ();
        if (is_object ($info)) $info = $info->toArray ();
        if (empty($info)) {
            Common::res (['redirect' => true]);
        }

        $info['rank'] = OpenModel::supportRank ($info['hot'], $info['id']);

        Common::res (['data' => compact ('info')]);
    }

    public function remove()
    {
        $open = $this->req ('open_id', 'integer', 0);
        $info = OpenModel::get ($open);
        if ($info) {
            $this->getUser ();
            if ($info['user_id'] != $this->uid) {
                Common::res (['code' => 1, 'msg' => "你无权访问"]);
            }

            Db::startTrans ();
            try {
                OpenModel::destroy ($open, true);
                OpenRank::destroy (['open_id' => $open], true);
                Db::commit ();
            } catch (\Throwable $throwable) {
                Db::rollback ();
                Common::res (['code' => 1, 'msg' => "删除图片失败，请稍后再试"]);
            }
        }

        Common::res (['msg' => '图片已删除']);
    }

    public function select()
    {
        $page = $this->req ('page', 'integer', 1);
        $size = $this->req ('size', 'integer', 10);
        $type = input ('type', OpenModel::NORMAL);
        $rankType = input ('rank', 'rank');

//        $this->checkType ($type);
        $map = compact ('type');
        if ($rankType == 'my') {
            $this->getUser ();
            $map['user_id'] = $this->uid;
        }
        if ($rankType == 'star') {
            $this->getUser ();
            $star_id = UserStar::getStarId ($this->uid);
            if (empty($star_id)) {
                Common::res (['code' => 1, 'msg' => "请先加入圈子"]);
            }
            $map['star_id'] = $star_id;
        }
        // 列表
        $list = OpenModel::getRankList ($map, $page, $size, 0);
        if (is_object ($list)) $list = $list->toArray ();
        $newList = [];
        foreach ($list as $index => $item) {
            if ($type == 'rank') {
                $offset = $index;
                $left = bcmul (bcsub ($page, 1), $size);

                $item['rank'] = bcadd ($offset, $left);
            } else {
                $item['rank'] = OpenModel::supportRank ($item['hot'], $item['id']);
            }

            $openRank = OpenRank::with('UserInfo')
                ->where ('open_id', $item['id'])
                ->order ([
                    'count' => 'desc',
                    'create_time' => 'asc'
                ])
                ->limit (3)
                ->select ();

            if (is_object ($openRank)) $openRank = $openRank->toArray ();

            $item['open_rank'] = $openRank;

            $newList[$index] = $item;
        }

        Common::res (['data' => ['list' => $newList]]);
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
