<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserSprite extends Base
{
    // public function CfgSprite()
    // {
    //     return $this->belongsTo('CfgSprite', 'sprite_level', 'level');
    // }

    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**获取该用户宠物信息 */
    public static function getInfo($uid)
    {
        $item = self::where('user_id', $uid)->find();
        if (!$item['total_speed_coin']) $item['total_speed_coin'] = self::getTotalFarmCoin($uid);
        $item['total_speed_coin'] += BadgeUser::speedUp($uid);
        return $item;
    }

    /**获取(保存)农场金豆收益数额 */
    public static function getTotalFarmCoin($uid)
    {
        $item = self::where('user_id', $uid)->find();

        $total_coin = 0;
        $total_coin += CfgPetTree::where('level', $item['tree_1_level'])->value('coin');
        $total_coin += CfgPetTree::where('level', $item['tree_2_level'])->value('coin');
        $total_coin += CfgPetLand::where('level', $item['land_1_level'])->value('coin');
        $total_coin += CfgPetLand::where('level', $item['land_2_level'])->value('coin');

        $total_coin *= 1 + CfgPetHouse::where('level', $item['house_level'])->value('addon_rate');
        // 保存
        self::where('user_id', $uid)->update(['total_speed_coin' => $total_coin]);

        return $total_coin;
    }

    /**结算该用户宠物收益 */
    public static function settle($uid)
    {
        // 离线收益百分比
        $offlineEarnPercent = CfgPetSkillThird::getSkill($uid)['myskill']['percent'];
        // 判定离线的时间，单位秒
        $offlineSplitTime = 300;
        // 结算标准时间间隔，单位秒
        $spaceTime = 100;

        $spriteInfo = self::getInfo($uid);
        // 与上次结算的时间差
        $duraTime = time() - $spriteInfo['settle_time'];
        // 小于标准间隔时间80%，不结算
        if ($duraTime < $spaceTime * 0.8) return;
        if ($duraTime > $offlineSplitTime) {
            // 离线
            $data['mode'] = 1;
            $data['earn'] = round($duraTime / $spaceTime * $spriteInfo['total_speed_coin'] * $offlineEarnPercent);
            $data['percent'] = $offlineEarnPercent;
        } else {
            // 在线
            $data['mode'] = 0;
            $data['earn'] = $spriteInfo['total_speed_coin'];
        }

        Db::startTrans();
        try {
            if ($data['mode'] == 1) {
                (new User)->change($uid, [
                    'coin' => $data['earn']
                ], '离线收益');
            } else {
                (new User)->change($uid, [
                    'coin' => $data['earn']
                ]);
            }

            self::where('user_id', $uid)->update([
                'settle_time' => time(),
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $data;
    }

    /**农产升级 */
    public static function upgrade($uid, $type)
    {
        $userSprite = self::getInfo($uid);

        switch ($type) {
            case 0:
                // 精灵升级
                if (!$userSprite['need_stone']) Common::res(['code' => 1, 'msg' => '已经是满级了！']);
                $field = 'sprite_level';
                $need_stone = $userSprite['need_stone'];
                break;
            case 1:
                // 技能一挖钻石升级
                $field = 'skill_one_level';
                $nextSkill = CfgPetSkillFirst::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];

                break;
            case 2:
                // 技能二挖金豆升级
                $field = 'skill_two_level';
                $nextSkill = CfgPetSkillSecond::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];

                break;
            case 3:
                // 技能三离线升级
                $field = 'skill_three_level';
                $nextSkill = CfgPetSkillThird::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                // 要达到一定粉丝等级
                $userLevel = CfgUserLevel::getLevel($uid);
                if ($userLevel < $nextSkill['level']) Common::res(['code' => 1, 'msg' => '粉丝等级需达到' . $nextSkill['level'] . '级']);
                $need_stone = $nextSkill['stone'];

                break;
            case 5:
                // 房子升级
                $field = 'house_level';
                $nextSkill = CfgPetHouse::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];
                break;

            case 6:
                // 树1升级
                $field = 'tree_1_level';
                $nextSkill = CfgPetTree::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];
                break;

            case 7:
                // 树2升级
                $field = 'tree_2_level';
                $nextSkill = CfgPetTree::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];

                break;
            case 8:
                // 地1升级
                $field = 'land_1_level';
                $nextSkill = CfgPetLand::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];
                break;

            case 9:
                // 地2升级
                $field = 'land_2_level';
                $nextSkill = CfgPetLand::get(['level' => $userSprite[$field] + 1]);
                if (!$nextSkill) Common::res(['code' => 1, 'msg' => '已经是顶级了！']);
                $need_stone = $nextSkill['stone'];

                break;
            default:
                # code...
                break;
        }

        Db::startTrans();
        try {
            self::where(['user_id' => $uid])->update([
                $field => $nextSkill['level'],
            ]);

            (new User())->change($uid, [
                'stone' => -$need_stone,
            ], '农场升级');
            
            $total_speed_coin = self::getTotalFarmCoin($uid);
            BadgeUser::addRec($uid, 4, $total_speed_coin);//stype=4产量徽章
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**  技能升级列表*/
    public static function getSkill($uid, $type)
    {
        if ($type == 1) {
            // 挖钻石
            $data = CfgPetSkillFirst::getSkill($uid);
        } else if ($type == 2) {
            // 挖金豆
            $data = CfgPetSkillSecond::getSkill($uid);
        } else if ($type == 3) {
            // 离线
            $data = CfgPetSkillThird::getSkill($uid);
        } else if ($type == 5) {
            // 房子升级
            $data = CfgPetHouse::getSkill($uid);
        } else if ($type == 6) {
            // 树1
            $data = CfgPetTree::getSkill($uid, 1);
        } else if ($type == 7) {
            // 树2
            $data = CfgPetTree::getSkill($uid, 2);
        } else if ($type == 8) {
            // 地1
            $data = CfgPetLand::getSkill($uid, 1);
        } else if ($type == 9) {
            // 地2
            $data = CfgPetLand::getSkill($uid, 2);
        }


        return $data;
    }

    public static function skillSettle($uid, $type)
    {
        if ($type == 1) {
            $data['num'] = CfgPetSkillFirst::skillSettle($uid);
            $data['msg'] = '钻石+' . $data['num'];
        } else if ($type == 2) {
            // 搞点金豆
            $data['num'] = CfgPetSkillSecond::skillSettle($uid);
            $data['msg'] = '金豆+' . $data['num'];
        } else if ($type == 3) { }

        return $data;
    }
}
