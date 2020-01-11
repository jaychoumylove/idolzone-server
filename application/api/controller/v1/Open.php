<?php

namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\CfgItem;
use app\api\model\GuideCron;
use app\api\model\Open as OpenModel;
use app\api\model\OpenTop;
use app\api\model\UserItem;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;

class Open extends Base
{

    public function upload()
    {
        $imgUrl = $this->req('img_url', 'require');
        $this->getUser();
        $starId = UserStar::getStarId($this->uid);
        if (!$starId) Common::res(['code' => 1, 'msg' => '请先加入一个圈子']);

        $todayDone = OpenModel::where('user_id', $this->uid)->whereTime('create_time', 'd')->value('id');
        if ($todayDone) Common::res(['code' => 1, 'msg' => '每日只可上传一张']);

        OpenModel::create(['user_id' => $this->uid, 'star_id' => $starId, 'img_url' => $imgUrl]);

        Common::res();
    }

    public function select()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $this->getUser();

        if ($page == 1) {
            // 昨日榜首
            $res['yestoday'] = OpenTop::get(['date' => date("Ymd", strtotime("-1 day"))]);
            $res['yestoday']['tomorrow'] = date("m", strtotime("+1 day")) . '月' . date("d", strtotime("+1 day")) . '日';
        }
        // 列表
        $res['list'] = OpenModel::getRankList($page, $size, 0);

        // 礼物
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
            if (!$value['self']) $value['self'] = 0;
        }
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        OpenModel::settle();
    }

    public function today()
    {
        $img = GuideCron::where('start_time', '<', time())->where('end_time', '>', time())->value('open_img');
        if (!$img) {
            $img = Cfg::getCfg('open_img');
        }
        Common::res(['data' => $img]);
    }
}
