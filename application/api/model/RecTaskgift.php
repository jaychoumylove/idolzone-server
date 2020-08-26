<?php
namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use Exception;
use think\Db;
use think\Model;

class RecTaskgift extends Base
{

    /**
     * 进度
     */
    public static function getProgress($uid, $cid)
    {
        return self::where([
            'user_id' => $uid,
            'cid' => $cid
        ])->column('task_id');
    }

    /**
     * 领奖励
     */
    public static function settleHandle($cid, $task_id, $awardsList, $uid)
    {
        Db::startTrans();
        try {
            self::create([
                'user_id' => $uid,
                'task_id' => $task_id,
                'cid' => $cid
            ]);
            
            $awards = json_decode($awardsList[$task_id]['awards'],true);            
            if(isset($awards['badge'])){
                BadgeUser::addRec($uid, 7, 1, $awards['badge']['id']);//冬至徽章
                unset($awards['badge']);
            }
            if ($cid == 1 && $task_id == 7) {
                $relation = UserRelation::where('ral_user_id', $uid)->find();
                if ($relation) {
                    $rer_user_id = $relation['rer_user_id'];
                    $starId = UserStar::getStarId ($rer_user_id);
                    $exited = empty($starId); // true 已退圈 false 未退圈
                    if ($rer_user_id && empty($exited)) {
                        $status = Cfg::checkInviteAssistTime();
                        if ($status) {
                            $platform = \app\api\model\User::where('id', $rer_user_id)->value('platform');
                            if ($platform == "MP-WEIXIN") {
                                UserInvite::recordInvite($rer_user_id, $starId);
                                \app\api\service\Star::addInvite($starId);
                            }
                        }
                    }
                }
            }
            (new User())->change($uid, $awards, $awardsList[$task_id]['title'] . '奖励');
            
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
