<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\User;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\CfgShareTitle;
use app\api\model\Cfg;
use app\base\service\Common;
use app\api\model\UserRelation;
use app\api\model\ShareMass;
use app\api\model\UserFather;
use app\api\service\Star;
use app\api\model\Star as StarModel;
use app\api\model\RecStarChart;
use app\api\model\Article;
use app\api\model\CfgAds;
use app\api\model\UserSprite;
use app\base\service\WxAPI;
use app\api\model\CfgItem;
use app\api\model\CfgShare;
use app\api\model\Notice;
use app\api\model\UserItem;
use GatewayWorker\Lib\Gateway;
use app\api\model\UserExt;
use app\api\model\Prop;
use app\api\model\UserProp;
use app\api\model\UserWxgroup;
use app\api\model\Wxgroup;
use app\api\model\WxgroupDynamic;
use app\api\model\WxgroupMass;

class Page extends Base
{
    public function app()
    {
        $this->getUser();

        $rer_user_id = input('referrer', 0);
        if ($rer_user_id) {
            // 拉新关系
            UserRelation::saveNew($this->uid, $rer_user_id);
        }

        $res['userInfo'] = User::where(['id' => $this->uid])->field('id,nickname,avatarurl,type')->find();
        $res['userCurrency'] = UserCurrency::getCurrency($this->uid);
        $res['userExt'] = UserExt::where('user_id', $this->uid)->find();

        $userStar = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        if (!$userStar) {
            $res['userStar'] = [];
        } else {
            $starInfo = $userStar['star'];
            $starInfo['captain'] = $userStar['captain'];
            $res['userStar'] = $userStar['star'];
        }

        // 顺便获取分享信息
        $res['config'] = Cfg::getList();
        $res['config']['share_text'] = CfgShareTitle::getOne();

        // $spriteUpgrade = UserSprite::getInfo($this->uid, $this->uid)['need_stone'];
        // $stone = UserCurrency::where(['uid' => $this->uid])->value('stone');

        // if ($stone >= $spriteUpgrade) {
        //     $res['upSprite'] = true;
        // }

        Common::res(['data' => $res]);
    }

    public function group()
    {
        $starid = input('starid');
        $client_id = input('client_id');
        $this->getUser();

        if (!$starid) Common::res(['code' => 100]);

        $res['starInfo'] = StarModel::with('StarRank')->where(['id' => $starid])->find();
        if (date('md') == $res['starInfo']['birthday']) {
            $res['starInfo']['isBirth'] = true;
        }

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');

        $res['userRank'] = UserStar::getRank($starid, 'thisday_count', 1, 6);
        $res['captain'] = UserStar::where('user_id', $this->uid)->value('captain');
        // 聊天内容
        $res['chartList'] = RecStarChart::getLeastChart($starid);
        // 加入聊天室
        Gateway::joinGroup($client_id, 'star_' . $starid);
        $res['disLeastCount'] = StarModel::disLeastCount($starid);

        // $res['mass'] = ShareMass::getMass($this->uid);

        // $res['invitList'] = [
        //     'list' => UserRelation::fixByType(1, $this->uid, 1, 10),
        //     'award' => Cfg::getCfg('invitAward'),
        //     'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        // ];

        $res['article'] = Notice::where('1=1')->order('create_time desc,id desc')->find();

        // 礼物
        // $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        // foreach ($res['itemList'] as &$value) {
        //     $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
        //     if (!$value['self']) $value['self'] = 0;
        // }

        Common::res(['data' => $res]);
    }

    public function giftPackage()
    {
        $this->getUser();
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
            if (!$value['self']) $value['self'] = 0;
        }

        Common::res(['data' => $res]);
    }

    public function giftCount()
    {
        $this->getUser();
        $res = UserItem::where(['uid' => $this->uid])->sum('count');
        Common::res(['data' => $res]);
    }

    public function prop()
    {
        Common::res(['data' => Prop::all()]);
    }

    public function myprop()
    {
        $this->getUser();
        $res['list'] = UserProp::getList($this->uid);
        Common::res(['data' => $res]);
    }

    /**游戏试玩 */
    public function game()
    {
        $type = $this->req('type', 'integer', 0);
        if ($type == 1) {
            $w = ['show' => 1];
        } else {
            $w = '1=1';
        }
        Common::res(['data' => CfgAds::where($w)->order('sort asc')->select()]);
    }

    /**群集结信息 */
    public function groupMass()
    {
        $gid = $this->req('gid', 'integer');
        $star_id = $this->req('star_id', 'integer');

        UserWxgroup::massSettle();

        $res = UserWxgroup::massStatus($gid);
        // 集结成员
        if ($res['status'] != 0) {
            $res['list'] = UserWxgroup::with('User')->where('wxgroup_id', $gid)
                ->whereTime('mass_join_at', 'between', [$res['massStartTime'], $res['massEndTime']])->order('mass_join_at asc')->select();
        } else {
            $res['list'] = [];
        }
        // star信息
        $res['star'] = StarModel::where('id', $star_id)->field('name,head_img_s')->find();
        Common::res(['data' => $res]);
    }

    public function wxgroup()
    {
        $this->getUser();
        // 集结动态
        // $res['dynamic'] = array_reverse(WxgroupDynamic::where('1=1')->order('id desc')->limit(30)->select());

        // 群日贡献排名
        $res['groupList'] = Wxgroup::with('star')->order('thisday_count desc')->limit(10)->select();
        foreach ($res['groupList'] as &$group) {
            $group['userRank'] = UserWxgroup::with('user')->where('wxgroup_id', $group['id'])->order('thisday_count desc')->field('user_id,thisday_count')->limit(5)->select();
        }

        // 贡献奖励
        $res['reback'] = UserWxgroup::where('user_id', $this->uid)->sum('daycount_reback');

        Common::res(['data' => $res]);
    }

    /**广场 */
    public function square()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $star_id = $this->req('star_id', 'require');

        $this->getUser();

        // 文章列表
        $res['article'] = Article::getList($star_id, $page, $size);
        // 是否订阅
        $res['subscribe'] = UserStar::where('user_id', $this->uid)->value('article_subscribe');
        // 明星信息
        $res['starInfo'] = StarModel::with('StarRank')->where(['id' => $star_id])->field('id,head_img_s,name,square_bg_img,square_bg_color')->find();

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');
        Common::res(['data' => $res]);
    }
}
