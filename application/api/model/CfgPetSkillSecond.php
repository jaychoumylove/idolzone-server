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
        $userSprite = UserSprite::where('user_id', $uid)->field('skill_two_level,skill_two_times,skill_two_offset,total_speed_coin')->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['skill_two_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['skill_two_level'] + 1)->find();

        // 今日剩余使用次数
        $dayMaxCount = $userSprite['skill_two_level'];
        $data['remainTimes'] = $dayMaxCount - $userSprite['skill_two_times'];
        // 今日剩余使用钻石次数
        $dayMaxCount = 10;
        $data['remainOffset'] = $dayMaxCount - $userSprite['skill_two_offset'];

        $userLevel = CfgUserLevel::getLevel($uid);
        // 基础金豆产量
        $data['baseCount'] = ceil($userSprite['total_speed_coin'] * 0.04 * $userSprite['skill_two_level'] * 0.8 * $userLevel * 2.4 * 3.6);
        $data['rate'] = PetSkilltwoRate::getRate($uid);

        return $data;
    }

    /**挖钻石结算 */
    public static function skillSettle($uid)
    {
        $skill = self::getSkill($uid);
        if ($skill['remainOffset'] > 0) {
            Db::startTrans();
            try {
                
                // 使用可获得：
                // 农场产量*0.04*技能等级*0.8*粉丝等级*2.4*3.6的金豆。
                $spriteInfo = UserSprite::where('user_id', $uid)->field('skill_two_level,skill_two_times,skill_two_offset,total_speed_coin')->find();

                $userLevel = CfgUserLevel::getLevel($uid);
                $count = ceil($spriteInfo['total_speed_coin'] * 0.04 * $spriteInfo['skill_two_level'] * 0.8 * $userLevel * 2.4 * 3.6);

                // 爆率
                $rateList = PetSkilltwoRate::getRate($uid);
                $rate = Common::lottery($rateList)['rate'];
                $count *= $rate;
                
                $currencyUpdate = ['coin' => $count];
                if ($skill['remainTimes'] <= 0) {
                    // 使用钻石爆豆
                    $currencyUpdate['stone'] = -1;
                    $isDone = UserSprite::where('user_id', $uid)->where('skill_two_offset','<',10)->update([
                        'skill_two_offset' => Db::raw('skill_two_offset+1')
                    ]);
                } else {
                    // 免费爆豆
                    $isDone = UserSprite::where('user_id', $uid)->where('skill_two_times','<',$skill['myskill']['times'])->update([
                        'skill_two_times' => Db::raw('skill_two_times+1')
                    ]);
                }
                
                if(!$isDone) Common::res(['code' => 1, 'msg' => '今日使用次数已用完，请明日再来']);      

                // 金豆全投
                $selfCoin = UserCurrency::where('uid', $uid)->value('coin');
                if ($selfCoin) {
                    $selfStarId = UserStar::getStarId($uid);
                    (new Star)->sendHot($selfStarId, $selfCoin, $uid, 1);
                }
                
                //获得爆豆
                (new User)->change($uid, $currencyUpdate, '挖到金豆');
                

                // 重置爆率
                // PetSkilltwoRate::where('user_id' , $uid)->update([
                //     'p_1' => 0,
                //     'p_3' => 0,
                //     'p_5' => 0,
                //     'p_8' => 0,
                //     'p_10' => 0,
                // ]);
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
