<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\ShareMass;
use app\base\service\Common;
use app\api\model\UserFather;
use app\api\model\Cfg;
use app\api\model\CfgSignin;
use app\api\model\User;
use think\Db;
use app\api\model\UserRelation;
use app\api\model\UserWxgroup;
use app\api\model\Wxgroup;
use app\api\model\WxgroupMass;
use app\api\service\User as AppUser;
use app\base\service\WxAPI;

class Share extends Base
{

    public function mass()
    {
        $this->getUser();
        Common::res(['data' => ShareMass::getMass($this->uid)]);
    }

    public function massStart()
    {
        $this->getUser();
        ShareMass::start($this->uid);
        Common::res([]);
    }

    /**
     * 从分享入口进入每次都会调用该接口
     */
    public function massJoin()
    {
        $rer_user_id = input('referrer');
        if (!$rer_user_id) Common::res(['code' => 100]);
        $this->getUser();
        // 拉新关系
        UserRelation::saveNew($this->uid, $rer_user_id);
        // 加入集结
        $massRerUser = ShareMass::join($rer_user_id, $this->uid);
        // 师徒关系
        UserFather::join($rer_user_id, $this->uid);

        Common::res(['data' => [
            'massRerUser' => $massRerUser
        ]]);
    }

    public function massSettle()
    {
        $this->getUser();

        $earn = ShareMass::settle($this->uid);
        Common::res(['data' => $earn]);
    }

    /**师徒关系列表 */
    public function father()
    {
        $this->getUser();
        $page = input('page', 1);

        $res = UserFather::getFatherList($this->uid);

        Common::res(['data' => $res]);
    }

    /**获取徒弟的收益 */
    public function sonEarn()
    {
        $sonUid = input('user_id', 0);
        $this->getUser();
        $earn = UserFather::sonEarn($this->uid, $sonUid);
        Common::res(['data' => $earn]);
    }

    public function checkEarn()
    {
        $this->getUser();
        $cur_contribute = UserFather::where(['father' => $this->uid])->max('cur_contribute');
        $earn = floor($cur_contribute * Cfg::getCfg('father_earn_per'));
        Common::res(['data' => $earn]);
    }

    public function breakFather()
    {
        $this->getUser();
        UserFather::breakFather($this->uid);
        Common::res([]);
    }

    /**团长红包 */
    public function groupAward()
    {
        $awardType = $this->req('award_type', 'integer');

        $this->getUser();
        CfgSignin::hongBao($awardType, $this->uid);
    }

    public function groupAdd()
    {
        $this->getUser();
        $appid = (new WxAPI())->appinfo['appid'];
        $sessionKey = User::where(['id' => $this->uid])->value('session_key');

        $encryptedData = input('encryptedData');
        $iv = input('iv');

        $res = Common::wxDecrypt($appid, $sessionKey, $encryptedData, $iv);
        if ($res['errcode'] !== 0) Common::res(['code' => 1, 'data' => $res]);

        $star_id = $this->req('star_id', 'integer');
        // 新增群
        $gid = Wxgroup::groupAdd($res['data']['openGId'], $star_id);
        // 新增群用户
        UserWxgroup::groupAddUser($gid, $this->uid);

        Common::res(['data' => [
            'gid' => $gid,
            'open_gid' => $res['data']['openGId']
        ]]);
    }

    public function groupMassJoin()
    {
        $gid = $this->req('gid', 'integer');
        $force = $this->req('force', 'integer');
        $this->getUser();
        UserWxgroup::massJoin($gid, $force, $this->uid);
        Common::res();
    }

    public function groupMassSettle()
    {
        UserWxgroup::massSettle();
    }

    /**群贡献奖励 */
    public function groupDayReback()
    {
        $this->getUser();

        $res['reback'] = UserWxgroup::groupDayReback($this->uid);

        Common::res(['data' => $res]);
    }
}
