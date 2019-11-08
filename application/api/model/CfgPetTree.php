<?php

namespace app\api\model;

use think\Model;

class CfgPetTree extends Model
{
    public static function getSkill($uid, $pos)
    {
        $userSprite = UserSprite::where('user_id', $uid)->field('tree_' . $pos . '_level')->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['tree_' . $pos . '_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['tree_' . $pos . '_level'] + 1)->find();

        return $data;
    }
}
