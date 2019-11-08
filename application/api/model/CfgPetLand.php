<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgPetLand extends Model
{
    public static function getSkill($uid, $pos)
    {
        $userSprite = UserSprite::where('user_id', $uid)->field('land_' . $pos . '_level')->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['land_' . $pos . '_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['land_' . $pos . '_level'] + 1)->find();

        return $data;
    }
}
