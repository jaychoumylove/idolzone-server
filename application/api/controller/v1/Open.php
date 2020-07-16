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
use think\Exception;

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

        $count = OpenModel::where ('user_id', $this->uid)->count ();
        if ($count >= 5) Common::res (['code' => 1, 'msg' => '最多只可以上传5张图片哦']);

        $data = [
            'type'    => $type,
            'user_id' => $this->uid,
            'star_id' => $starId,
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
            $updated = OpenModel::where('id', $id)->where('hot', $open['hot'])->update([
                'hot' => bcadd ($open['hot'], $hot)
            ]);
            if (empty($updated)) {
                Common::res (['code' => 1, 'msg' => "助力失败,请稍后再试"]);
            }
            (new \app\api\service\User())->change ($this->uid, ['flower' => -$hot], '【最美军装】开屏鲜花助力');
            (new \app\api\service\Star())->sendHot ($starId, $hot, $this->uid, 2);
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
        $info['rank'] = OpenModel::where('hot', '>', $info['hot'])->count ();
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

        $info['rank'] = OpenModel::where('hot', '>', $info['hot'])->count ();

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
                $res = OpenModel::destroy ($open, true);

                if (empty($res)) {
                    throw new Exception('删除失败');
                }

                $res = OpenRank::destroy (['open_id' => $open], true);
                if (empty($res)) {
                    throw new Exception('删除失败');
                }
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

        $this->checkType ($type);
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
            $item['rank'] = OpenModel::where('hot', '>', $item['hot'])->count ();

            if (is_object ($item['open_rank'])) $item['open_rank'] = $item['open_rank']->toArray();

            if (count($item['open_rank']) > 3) {
                $item['open_rank'] = array_filter ($item['open_rank'], function($key) {
                    return $key < 3;
                }, ARRAY_FILTER_USE_KEY);
            }

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
