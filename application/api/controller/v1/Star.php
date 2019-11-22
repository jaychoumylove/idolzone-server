<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\service\Star as StarService;
use app\api\model\Star as StarModel;
use app\base\service\Common;
use app\api\model\RecStarChart;
use app\api\model\User as UserModel;
use think\Db;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\base\service\WxAPI;
use app\api\model\UserSprite;
use GatewayWorker\Lib\Gateway;

class Star extends Base
{
    public function getInfo()
    {
        $starid =  $this->req('starid', 'integer');

        $star = StarModel::with('StarRank')->where(['id' => $starid])->find();
        if (date('md') == $star['birthday']) {
            $star['isBirth'] = true;
        }

        $starService = new StarService();
        $star['star_rank']['week_hot_rank'] = $starService->getRank($star['star_rank']['week_hot'], 'week_hot');
        // $star['star_rank']['month_hot_rank'] = $starService->getRank($star['star_rank']['month_hot'], 'month_hot');

        Common::res(['data' => $star]);
    }

    public function getChart()
    {
        $starid = $this->req('starid', 'integer');

        $res = RecStarChart::getLeastChart($starid);
        Common::res(['data' => $res]);
    }

    /**加入聊天室 */
    public function joinChart()
    {
        $star_id = $this->req('star_id', 'integer');
        $client_id = input('client_id');
        if (!$client_id || !$star_id) Common::res(['code' => 100]);

        Gateway::joinGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }

    public function leaveChart()
    {
        $star_id = $this->req('star_id', 'integer');
        $client_id = input('client_id');
        if (!$client_id || !$star_id) Common::res(['code' => 100]);

        Gateway::leaveGroup($client_id, 'star_' . $star_id);
        Common::res([]);
    }

    public function sendMsg()
    {
        $starid = $this->req('starid', 'integer');
        $content = $this->req('content', 'require');

        $this->getUser();

        RecStarChart::sendMsg($this->uid, $starid, $content);
        Common::res();
    }

    /**贡献人气 */
    public function sendHot()
    {
        $starid = $this->req('starid', 'integer');
        $hot = $this->req('hot', 'integer');
        // 1金豆 2鲜花
        $type = $this->req('type', 'integer', 0);
        $danmaku = $this->req('danmaku', 'integer', 1); // 是否推送打榜弹幕
        $this->getUser();

        (new StarService())->sendHot($starid, $hot, $this->uid, $type, $danmaku);

        Common::res(['data' => []]);
    }

    /**加入圈子 */
    public function follow()
    {
        $starid = $this->req('starid', 'integer');
        $this->getUser();

        $uid = UserStar::joinNew($starid, $this->uid);
        UserRelation::join($starid, $uid);
        
        Common::res();
    }

    /**偷花 */
    public function steal()
    {
        $starid = $this->req('starid', 'integer');
        $index = input('index');
        if (!$starid) Common::res(['code' => 100]);
        $this->getUser();

        $spriteLevel = UserSprite::where(['user_id' => $this->uid])->value('sprite_level');
        $stealCount = Cfg::getCfg('stealCount') * $spriteLevel;
        (new StarService())->steal($starid, $this->uid, $stealCount);

        UserExt::setTime($this->uid, $index);
        Common::res(['data' => ['count' => $stealCount]]);
    }

    /**明星动态 */
    public function dynamic()
    {
        $starid = $this->req('starid', 'integer');
        if (!$starid) Common::res(['code' => 100]);
        $res = Rec::with(['User ' => ['UserStar ' => ['Star']]])->where('target_star_id', $starid)->limit(10)->order('id desc')->select();

        Common::res(['data' => $res]);
    }
}
