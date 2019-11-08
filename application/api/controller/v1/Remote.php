<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Rec;
use app\api\model\UserSprite;
use app\base\service\Common;

class Remote extends Base
{

    public function zuimei()
    {
        $this->getUserByUnionid();

        // 今日贡献度
        $res['dayContribute'] = -Rec::where('user_id', $this->uid)->where('type', 2)->whereTime('create_time', 'd')->sum('coin');

        // 精灵等级
        $res['spriteLv'] = UserSprite::where('user_id', $this->uid)->value('sprite_level');

        Common::res(['data' => $res]);
    }
}
