<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\User as UserModel;
use app\base\service\Common;
use app\api\service\User as UserService;
use app\api\model\UserItem as UserItemModel;
use app\api\model\UserCurrency;
use app\api\model\UserStar;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\model\CfgShareTitle;
use app\api\model\Cfg;
use app\base\service\WxAPI;
use app\api\model\CfgSignin;
use GatewayWorker\Lib\Gateway;
use app\api\model\RecStarChart;
use think\Db;

class User extends Base
{
    /**用户登录 */
    public function login()
    {
        $code = $this->req('code', 'require'); // 登录code
        $res['platform'] = $this->req('platform', 'require', 'MP-WEIXIN'); // 平台
        $res['model'] = $this->req('model'); // 手机型号

        $res = (new UserService())->wxGetAuth($code, $res['platform']);

        $uid = UserModel::searchUser($res);
        $token = Common::setSession($uid);

        Common::res(['msg' => '登录成功', 'data' => ['token' => $token]]);
    }

    /**保存用户信息 */
    public function saveInfo()
    {
        $encryptedData = $this->req('encryptedData', 'require');
        $iv = $this->req('iv', 'require');

        $this->getUser();

        $appid = (new WxAPI())->appinfo['appid'];
        $sessionKey = UserModel::where('id', $this->uid)->value('session_key');

        // 解密encryptedData
        $res = Common::wxDecrypt($appid, $sessionKey, $encryptedData, $iv);
        if ($res['errcode']) Common::res(['code' => 1, 'msg' => $res['data']]);
        // 保存
        foreach ($res['data'] as $key => $value) {
            $saveData[strtolower($key)] = $value;
        }
        unset($saveData['watermark']);

        UserModel::where('id', $this->uid)->update($saveData);
        Common::res();
    }

    public function getInfo()
    {
        $uid = $this->req('user_id', 'integer');

        $res = UserModel::where('id', $uid)->field('id,nickname,avatarurl,type')->find();
        Common::res(['data' => $res]);
    }

    /**
     * 获取用户所有货币数量
     */
    public function getCurrency()
    {
        $this->getUser();
        $res = UserCurrency::getCurrency($this->uid);

        Common::res(['data' => $res]);
    }

    public function getStar()
    {
        $this->getUser();

        $res = UserStar::with('Star')->where(['user_id' => $this->uid])->order('id desc')->find();
        unset($res['star']['create_time']);
        Common::res(['data' => $res['star']]);
    }

    /**
     * 获取用户道具
     */
    public function getItem()
    {
        $this->getUser();

        $item = UserItemModel::getItem($this->uid);
        Common::res(['data' => $item]);
    }

    public function invitList()
    {
        $type = input('type', 0);
        $page = input('page', 1);
        $size = input('size', 10);

        $this->getUser();
        $res = UserRelation::fixByType($type, $this->uid, $page, $size);

        Common::res(['data' => [
            'list' => $res,
            'award' => Cfg::getCfg('invitAward'),
            'hasInvitcount' => UserRelation::with('User')->where(['rer_user_id' => $this->uid, 'status' => ['in', [1, 2]]])->count()
        ]]);
    }

    public function invitAward()
    {
        $ral_user_id = $this->req('ral_user_id', 'integer');
        $this->getUser();

        (new UserService())->getInvitAward($ral_user_id, $this->uid);
        Common::res([]);
    }

    /**
     * 绑定推送客户端id
     */
    public function bindClientId()
    {
        $client_id = input('client_id');
        if (!$client_id) Common::res(['code' => 100]);

        $this->getUser();

        Gateway::bindUid($client_id, $this->uid);
        Common::res([]);
    }

    public function stealTime()
    {
        $this->getUser();
        $res = UserExt::get(['user_id' => $this->uid]);
        $leftTime = json_decode($res['left_time']);
        foreach ($leftTime as &$value) {
            $time =  Cfg::getCfg('stealLimitTime') - (time() - $value);
            if ($time < 0) {
                $time = 0;
            }
            $value = $time;
        }
        Common::res(['data' => $leftTime]);
    }

    public function sayworld()
    {
        $content = $this->req('content', 'require');
        $this->getUser();
        // 格式化发言内容
        RecStarChart::verifyWord($content);
        // 扣除喇叭
        (new UserService())->change($this->uid, [
            'trumpet' => -1
        ], '喊话');

        $user = UserModel::where('id', $this->uid)->field('nickname,avatarurl')->find();
        // 推送socket消息
        Gateway::sendToAll(json_encode([
            'type' => 'sayworld',
            'data' => [
                'avatarurl' => $user['avatarurl'],
                'content' => $content,
                'nickname' => $user['nickname'],
            ],
        ], JSON_UNESCAPED_UNICODE));

        Common::res();
    }

    /**退出圈子 */
    public function exit()
    {
        $this->getUser();
        UserStar::exit($this->uid);
        Common::res([]);
    }

    public function signin()
    {
        $this->getUser();

        $cfg = CfgSignin::all();

        $res = (new UserService())->signin($this->uid);
        $res['cfg'] = $cfg;

        Common::res(['data' => $res]);
    }

    /**礼物兑换金豆 */
    public function recharge()
    {
        $item_id = input('item_id');
        $num = input('num');
        if (!$item_id || !$num || $num < 0) Common::res(['code' => 100]);
        $this->getUser();

        UserItemModel::recharge($this->uid, $item_id, $num);

        Common::res([]);
    }

    /**加好友 */
    public function addFriend()
    {
        $user_id = input('user_id');
        if (!$user_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $this->getUser();

        UserRelation::addFriend($this->uid, $user_id);

        Common::res();
    }

    /**删好友 */
    public function delFriend()
    {
        $user_id = input('user_id');
        if (!$user_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $this->getUser();

        UserRelation::delFriend($this->uid, $user_id);
        Common::res();
    }

    /**送给他人 */
    public function sendToOther()
    {
        $user_id = $this->req('user_id', 'integer');
        $num = $this->req('num', 'integer');
        $type = $this->req('type', 'require');
        $this->getUser();

        UserCurrency::sendToOther($this->uid, $user_id, $num, $type);
        Common::res();
    }


    public function sendItemToOther()
    {
        $user_id = input('user_id');
        $item_id = input('item_id'); // 礼物id
        if (!$user_id || !$item_id || $user_id == 'undefined') Common::res(['code' => 100]);

        $num = input('num', 1);
        $this->getUser();

        UserItemModel::sendItemToOther($this->uid, $user_id, $num, $item_id);
        Common::res();
    }

    public function forbidden()
    {
        $user_id = $this->req('user_id', 'integer');
        $this->getUser();
        if (UserStar::getStarId($user_id) != UserStar::getStarId($this->uid)) Common::res(['code' => 1]);

        $type = 2;

        UserModel::where('id', $user_id)->update(['type' => $type]);
        Common::res();
    }

    /**团战积分 */
    public function extraCurrency()
    {
        $this->getUser();
        $res['score'] = round(Db::name('pk_user_rank')->where('uid', $this->uid)->order('id desc')->value('score') / 10000);
        Common::res(['data' => $res]);
    }

    /**点赞 */
    public function like()
    {
        $user_id = $this->req('user_id', 'integer');
        $this->getUser();

        UserExt::like($this->uid, $user_id);
        Common::res();
    }
}
