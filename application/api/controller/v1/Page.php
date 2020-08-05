<?php

namespace app\api\controller\v1;

use app\api\model\Cfg_luckyDraw;
use app\api\model\CfgScrap;
use app\api\model\RecLuckyDrawLog;
use app\api\model\UserScrap;
use app\base\controller\Base;
use app\api\model\User;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\CfgShareTitle;
use app\api\model\Cfg;
use app\base\service\Common;
use app\api\model\UserRelation;
use app\api\service\Star;
use app\api\model\Star as StarModel;
use app\api\model\RecStarChart;
use app\api\model\Article;
use app\api\model\CfgAds;
use app\api\model\CfgItem;
use app\api\model\Notice;
use app\api\model\UserItem;
use GatewayWorker\Lib\Gateway;
use app\api\model\UserExt;
use app\api\model\Prop;
use app\api\model\UserProp;
use app\api\model\UserWxgroup;
use app\api\model\Wxgroup;
use think\Db;
use app\api\service\User as UserService;
use app\api\model\BadgeUser;
use app\api\model\GzhUser;
use app\api\model\GzhUserPush;
use app\api\model\RecPayOrder;
use app\api\model\FanclubUser;
use app\api\model\CfgShare;
use app\api\model\RecTaskfather;
use app\api\service\Sms;

class Page extends Base
{

    public function app()
    {
        $this->getUser();

        $rer_user_id = input('referrer', 0);
        $enterScene = $this->req('scene');// 场景
        if ($enterScene == 1001 || $enterScene == 1089) {
            // 添加到我的小程序
            UserExt::where('user_id', $this->uid)->update(['add_enter' => 1]);
        }
        if ($rer_user_id) {
            // 拉新关系
            UserRelation::saveNew($this->uid, $rer_user_id);
        }

        $res['userInfo'] = User::where([
            'id' => $this->uid
        ])->field('id,nickname,avatarurl,type,phoneNumber')->find();
        $res['userCurrency'] = UserCurrency::getCurrency($this->uid);
        $res['userExt'] = UserExt::where('user_id', $this->uid)->find();
        $res['userExt']['totalCount'] = UserStar::where('user_id', $this->uid)->max('total_count');

        $userStar = UserStar::with('Star')->where([
            'user_id' => $this->uid
        ])
            ->order('id desc')
            ->find();
        if (!$userStar) {
            $res['userStar'] = [];
        } else {
            $starInfo = $userStar['star'];
            $starInfo['captain'] = $userStar['captain'];
            $res['userStar'] = $userStar['star'];
        }

        // 获取分享信息
        $res['config'] = Cfg::getList();
        $userTotalPay = RecPayOrder::where('tar_user_id', $this->uid)->where('pay_time', 'not null')->sum('total_fee');
        if ($res['userStar'] && $res['userStar']['birthday'] == (int) date('md') && $res['userStar']['open_img']) {
            // 生日弹框条件
            $res['config']['index_open']['img'] = $res['userStar']['open_img'];
            $res['config']['isBirthday']=1;
        } else if ($userTotalPay < Cfg::getCfg('open_img_show_charge')) {
            // 充值小于n元的用户隐藏首页弹图
            $res['config']['index_open'] = null;
        }

        $res['config']['share_text'] = CfgShareTitle::getOne();
        $res['config']['share_cfg'] = CfgShare::column('title,imageUrl,path','id');

        //生成我的徽章数据
        BadgeUser::initBadge($this->uid);

        // 师徒每日登录
        RecTaskfather::checkIn($this->uid);

        Common::res([
            'data' => $res
        ]);
    }

    public function group()
    {
        $starid = input('starid');
        $client_id = input('client_id');
        $this->getUser();

        if (!$starid)
            Common::res([
                'code' => 100
            ]);

        $res['starInfo'] = StarModel::with('StarRank')->where([
            'id' => $starid
        ])->find();
        if (date('md') == $res['starInfo']['birthday']) {
            $res['starInfo']['isBirth'] = true;
        }

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');

        $res['userRank'] = UserStar::getRank($starid, 'thisday_count', 1, 6);
        if (!$res['userRank']) $res['userRank'] = UserStar::getRank($starid, 'total_count', 1, 6);

        $res['captain'] = UserStar::where('user_id', $this->uid)->value('captain');

        $res['is_blessing_gifts'] = UserExt::where('user_id', $this->uid)->value('is_blessing_gifts');
        if(input('platform')!='MP-WEIXIN'){
            $res['is_blessing_gifts']=1;
        }
        if(!$res['starInfo']['chat_off']){
            // 聊天内容
            $res['chartList'] = RecStarChart::getLeastChart($starid);
            // 加入聊天室
            Gateway::joinGroup($client_id, 'star_' . $starid);
        }
        
        $res['disLeastCount'] = StarModel::disLeastCount($starid);

        // $res['mass'] = ShareMass::getMass($this->uid);

        // $res['invitList'] = [
        // 'list' => UserRelation::fixByType(1, $this->uid, 1, 10),
        // 'award' => Cfg::getCfg('invitAward'),
        // 'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        // ];

        $res['article'] = Notice::where('1=1')->order('create_time desc,id desc')->find();
        $res['fanclub_id'] = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');

        // 礼物
        // $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        // foreach ($res['itemList'] as &$value) {
        // $value['self'] = UserItem::where(['uid' => $this->uid, 'item_id' => $value['id']])->value('count');
        // if (!$value['self']) $value['self'] = 0;
        // }

        Common::res([
            'data' => $res
        ]);
    }

    public function giftPackage()
    {
        $this->getUser();
        $res['itemList'] = CfgItem::where('1=1')->order('count asc')->select();
        foreach ($res['itemList'] as &$value) {
            $value['self'] = UserItem::where([
                'uid' => $this->uid,
                'item_id' => $value['id']
            ])->value('count');
            if (!$value['self'])
                $value['self'] = 0;
        }

        Common::res([
            'data' => $res
        ]);
    }

    public function giftCount()
    {
        $this->getUser();
        $res = UserItem::where([
            'uid' => $this->uid
        ])->sum('count');
        Common::res([
            'data' => $res
        ]);
    }

    public function prop()
    {
        $rechargeSwitch = Cfg::getCfg('ios_switch');
        if (input('platform') == 'MP-WEIXIN' && $rechargeSwitch == 3) {
            $propList = Prop::all(function ($query) {
                $query->where('get_type', Prop::STORE)->where('id', 'not in', [1, 2])->order('point asc');
            });
        } else {
            $propList = Prop::all(function ($query) {
                $query->where('get_type', Prop::STORE)->order('point asc');
            });
        }
        // $propList = Prop::all(function ($query) {
        //     $query->order('point asc');
        // });

        Common::res(['data' => $propList]);
    }

    public function myprop()
    {
        $this->getUser();

        //触发用户PK积分转移
        $score = Db::name('pk_user_rank')->where('uid', $this->uid)->order('last_pk_time desc')->value('score');
        if ($score) {

            Db::startTrans();
            try {
                (new UserService)->change($this->uid, ['point' => $score], 'PK积分转移');
                Db::name('pk_user_rank')->where('uid', $this->uid)->update(['score' => 0]);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }


        $res['list'] = UserProp::getList($this->uid);
        $res['currentPoint'] = UserCurrency::getCurrency($this->uid)['point'];
        $res['pointNoticeId'] = 15;
        Common::res([
            'data' => $res
        ]);
    }

    public function propExchange()
    {
        $proid = $this->req('proid', 'integer', 0);
        $count = $this->req('count', 'integer', 0);
        $this->getUser();
        $res = UserProp::exchangePoint($this->uid, $proid, $count);
        Common::res([
            'data' => $res
        ]);
    }

    public function propUse()
    {
        $userprop_id = $this->req('userprop_id', 'integer', 0);
        $this->getUser();
        $res = UserProp::useIt($this->uid, $userprop_id);
        Common::res([
            'data' => self::myprop()
        ]);
    }
    /**
     * 游戏试玩
     */
    public function game()
    {
        $type = $this->req('type', 'integer', 0);
        $w = ['platform' => input('platform')];
        if ($type == 1) {
            $w['show'] = 1;
        }
        Common::res([
            'data' => CfgAds::where($w)->order('sort asc')->select()
        ]);
    }

    /**
     * 群集结信息
     */
    public function groupMass()
    {
        $gid = $this->req('gid', 'integer');
        $star_id = $this->req('star_id', 'integer');

        UserWxgroup::massSettle();

        $res = UserWxgroup::massStatus($gid);
        // 集结成员
        if ($res['status'] != 0) {
            $res['list'] = UserWxgroup::with('User')->where('wxgroup_id', $gid)
                ->whereTime('mass_join_at', 'between', [
                    $res['massStartTime'],
                    $res['massEndTime']
                ])
                ->order('mass_join_at asc')
                ->select();
        } else {
            $res['list'] = [];
        }
        // star信息
        $res['star'] = StarModel::where('id', $star_id)->field('name,head_img_s')->find();
        Common::res([
            'data' => $res
        ]);
    }

    public function wxgroup()
    {
        $this->getUser();
        // 集结动态
        // $res['dynamic'] = array_reverse(WxgroupDynamic::where('1=1')->order('id desc')->limit(30)->select());

        // 群日贡献排名
        $res['groupList'] = Wxgroup::with('star')->order('thisday_count desc')
            ->limit(10)
            ->select();
        foreach ($res['groupList'] as &$group) {
            $group['userRank'] = UserWxgroup::with('user')->where('wxgroup_id', $group['id'])
                ->order('thisday_count desc')
                ->field('user_id,thisday_count')
                ->limit(5)
                ->select();
        }

        // 贡献奖励
        $res['reback'] = UserWxgroup::where('user_id', $this->uid)->sum('daycount_reback');

        Common::res([
            'data' => $res
        ]);
    }

    /**
     * 广场
     */
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
        $res['starInfo'] = StarModel::with('StarRank')->where([
            'id' => $star_id
        ])
            ->field('id,head_img_s,name,square_bg_img,square_bg_color')
            ->find();

        $starService = new Star();
        $res['starInfo']['star_rank']['week_hot_rank'] = $starService->getRank($res['starInfo']['star_rank']['week_hot'], 'week_hot');
        Common::res([
            'data' => $res
        ]);
    }

    /**公众号订阅列表 */
    public function gzhSubscribe()
    {
        $this->getUser();
        $gzh_appid = 'wx3507654fa8d00974';// 服务号APPID
        $res['subscribe'] = GzhUser::where('gzh_appid', $gzh_appid)->where('user_id', $this->uid)->value('subscribe');
        if ($res['subscribe']) {
            $res['list'] = GzhUserPush::getList($this->uid);
        }

        Common::res(['data' => $res]);
    }
    
    /*
     * 发送短信
     * */
    public function sendSms()
    {

        $phoneNumber = input('phoneNumber',0);
        $this->getUser();
        
        $phoneNumber = strpos($phoneNumber,'86')!==false && strpos($phoneNumber,'86')==0 ? substr($phoneNumber, -11) : $phoneNumber;
        $hasExist = User::where('phoneNumber',$phoneNumber)->count();
        if($hasExist) Common::res(['code' => 1, 'msg' => '该号码已被占用']);
        
        $sms = json_decode(UserExt::where('user_id',$this->uid)->value('sms'),true);
        if(isset($sms['phoneNumber']) && time()-$sms['sms_time']<=24*3600 && $sms['phoneNumber']==$phoneNumber ) Common::res(['code' => 1, 'msg' => '验证码已发送，1天只能发送一次']);
        
        $content = (new Sms())->send($phoneNumber);
        UserExt::where('user_id',$this->uid)->update(['sms'=>json_encode($content)]);
        if($content['Code'] != 'OK') Common::res(['code' => 1, 'msg' => $content['Message']]);
        Common::res();
    }

    public function luckyCharge()
    {
        $this->getUser ();

        $config = Cfg::getCfg (Cfg::RECHARGE_LUCKY);

        $forbiddenUser = array_key_exists ('forbidden_user', $config) ? $config['forbidden_user']: [];

        $rec = RecLuckyDrawLog::with(['user'])
            ->where('user_id', 'not in', $forbiddenUser)
            ->order('create_time', 'desc')
            ->limit (6)
            ->select ();

        // 触发更新用户碎片过期
        try {
            $currentTime = date ('Y-m-d H:i:s');

            if ($currentTime > $config['scrap_time']['end_time']) {
                // 碎片是否过期
                $userExt = UserExt::where('user_id', $this->uid)->find ();
                if (empty($userExt['scrap_time'])) {
                    // 是否更新过
                    if ($userExt['scrap'] || $userExt['last_scrap']) {
                        // 是否需要更新
                        UserExt::where('user_id', $this->uid)->update([
                            'scrap' => 0,
                            'last_scrap' => $userExt['scrap'],
                            'scrap_time' => $currentTime
                        ]);
                    }
                }
            }
        }catch (\Throwable $throwable) {}

        $scrap = CfgScrap::where('status', CfgScrap::ON)->select ();
        if (is_object ($scrap)) $scrap = $scrap->toArray ();

        $scrapIds = array_column ($scrap, 'id');

        $userScrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');

        $userScraps = UserScrap::where('user_id', $this->uid)
            ->where('scrap_id', 'in', $scrapIds)
            ->select ();
        if (is_object ($userScraps)) $userScraps = $userScraps->toArray ();

        $userScrapDict = array_column ($userScraps, null, 'scrap_id');
        foreach ($scrap as $index => $item) {
            $item['has_number'] = $userScrapNum;
            $item['has_exchange'] = 0;
            $item['percent'] = bcmul (bcdiv ($userScrapNum, $item['count'], 2), 100);
            if (array_key_exists ($item['id'], $userScrapDict)) {
                $item['has_exchange'] = $userScrapDict[$item['id']]['exchange'];
            }
        }

        $data[Cfg::RECHARGE_LUCKY] = $config;
        $data['lucky_log'] = $rec;
        $data['scrap_list'] = $scrap;
        Common::res (compact ('data'));
    }
}
