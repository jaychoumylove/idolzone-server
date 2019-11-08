<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class CfgPetSkillFirst extends Base
{
    /**return true 可领取 */
    public static function getSkill($uid)
    {
        $userSprite = UserSprite::where('user_id', $uid)->find();

        // 我的等级
        $data['myskill'] = self::where('level', $userSprite['skill_one_level'])->find();
        // 下一等级
        $data['nextskill'] = self::where('level', $userSprite['skill_one_level'] + 1)->find();

        if (time() - $userSprite['skill_one_starttime'] >= $data['myskill']['time']) {
            // 可领取
            $data['remainTime'] = true;
        } else {
            // 倒计时
            $data['remainTime'] = $data['myskill']['time'] - (time() - $userSprite['skill_one_starttime']);
        }

        return $data;
    }

    /**挖钻石结算 */
    public static function skillSettle($uid)
    {
        $skill = self::getSkill($uid);

        if ($skill['remainTime'] === true) {
            $stoneNum = 5;

            Db::startTrans();
            try {
                (new User)->change($uid, [
                    'stone' => $stoneNum,
                ], '挖到钻石');

                UserSprite::where('user_id', $uid)->update(['skill_one_starttime' => time()]);

                RecTask::addRec($uid, 3);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }

            return $stoneNum;
        } else {
            Common::res(['code' => 1, 'msg' => '钻石正在挖取中...']);
        }
    }
}
