<?php

namespace app\api\model;

use app\api\service\Star;
use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class CfgPetSkillSecond extends Base
{
    /**return true 可领取 */
    public static function getSkill($uid)
    {
        $userSprite = UserSprite::where('user_id', $uid)->field('skill_two_level,skill_two_times')->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['skill_two_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['skill_two_level'] + 1)->find();

        // 今日剩余使用次数
        $dayMaxCount = $userSprite['skill_two_level'];
        $data['remainTimes'] = $dayMaxCount - $userSprite['skill_two_times'];

        return $data;
    }

    /**挖钻石结算 */
    public static function skillSettle($uid)
    {
        $skill = self::getSkill($uid);

        if ($skill['remainTimes'] > 0) {
            Db::startTrans();
            try {
                // 金豆全投
                $selfCoin = UserCurrency::where('uid', $uid)->value('coin');
                $selfStarId = UserStar::getStarId($uid);
                (new Star)->sendHot($selfStarId, $selfCoin, $uid, 1);
                // 使用可获得农场100秒产量x技能等级x粉丝等级的金豆。
                // 每日按照技能等级最多使用十次。
                $spriteInfo = UserSprite::getInfo($uid);

                $spriteProduce = $spriteInfo['total_speed_coin'];
                $skillLevel = $spriteInfo['skill_two_level'];
                $userLevel = CfgUserLevel::getLevel($uid);
                $count = ceil($spriteProduce * $skillLevel * $userLevel);

                (new User)->change($uid, [
                    'coin' => $count,
                ], '挖到金豆');

                UserSprite::where('user_id', $uid)->update([
                    'skill_two_times' => Db::raw('skill_two_times+1')
                ]);

                RecTask::addRec($uid, 4);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        } else {
            Common::res(['code' => 1, 'msg' => '今日使用次数已用完，请明日再来']);
        }

        return $count;
    }
}
