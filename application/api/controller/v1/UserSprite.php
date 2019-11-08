<?php

namespace app\api\controller\v1;

use app\api\model\CfgPetSkillFirst;
use app\api\model\CfgPetSkillSecond;
use app\api\model\CfgPetSkillThird;
use app\api\model\FarmHelp;
use app\base\controller\Base;
use app\api\model\UserSprite as UserSpriteModel;
use app\base\service\Common;

class UserSprite extends Base
{
    public function info()
    {
        $user_id = $this->req('user_id', 'integer');
        $res['spriteInfo'] = UserSpriteModel::getInfo($user_id);
        $res['skillOneRemainTime'] = CfgPetSkillFirst::getSkill($user_id)['remainTime'];
        $res['skillTwoRemainTimes'] = CfgPetSkillSecond::getSkill($user_id)['remainTimes'];
        $res['skillThreePercent'] = CfgPetSkillThird::getSkill($user_id)['myskill']['percent'];
        Common::res(['data' => $res]);
    }

    public function settle()
    {
        $this->getUser();
        $res = UserSpriteModel::settle($this->uid);
        Common::res(['data' => $res]);
    }

    public function upgrade()
    {
        $this->getUser();
        $type = $this->req('type', 'integer', 0);
        UserSpriteModel::upgrade($this->uid, $type);
        Common::res();
    }

    public function getSkill()
    {
        $type = $this->req('type', 'integer', 1);
        $this->getUser();
        $data = UserSpriteModel::getSkill($this->uid, $type);
        Common::res(['data' => $data]);
    }

    public function skillSettle()
    {
        $type = $this->req('type', 'integer', 1);
        $this->getUser();
        $data = UserSpriteModel::skillSettle($this->uid, $type);
        Common::res(['data' => $data]);
    }

    public function helplist()
    {
        $this->getUser();
        $list = FarmHelp::with(['helper'])->where('user_id', $this->uid)->whereTime('create_time', 'd')->limit(8)->select();

        Common::res(['data' => $list]);
    }

    /**帮好友加速农场 */
    public function helpspeed()
    {
        $user_id = $this->req('user_id', 'integer');
        $this->getUser();
        FarmHelp::helpspeed($user_id, $this->uid);
    }

    public function helpstart()
    {
        $this->getUser();
        FarmHelp::helpstart($this->uid);
    }
}
