<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgPetHouse extends Base
{
    public static function getSkill($uid)
    {
        $userSprite = UserSprite::where('user_id', $uid)->field('house_level')->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['house_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['house_level'] + 1)->find();

        return $data;
    }
}
