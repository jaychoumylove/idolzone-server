<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgBadge extends Base
{
    public static function getList($uid)
    {
        $list = self::all();
        // 正佩戴的徽章
        $curBadgeId = UserExt::where('user_id', $uid)->value('badge_id');

        // 拉新数
        $dataInvitCount = UserRelation::invitCount($uid);

        foreach ($list as $key => &$value) {
            // status默认未达成
            $value['status'] = 0;
            switch ($value['type']) {
                case 1:
                    // 拉新
                    $value['doneTimes'] = $dataInvitCount;
                    if ($value['count'] <= $dataInvitCount) {
                        $value['status'] = 1;
                    }

                    break;
                default:
                    # code...
                    break;
            }

            if ($curBadgeId == $value['id']) {
                $value['status'] = 2;
            }
        }

        return $list;
    }

    /**徽章使用 */
    public static function badgeUse($badgeId, $uid)
    {
        $isDone = UserExt::where('user_id', $uid)->update(['badge_id' => $badgeId]);
        if (!$isDone) {
            UserExt::where('user_id', $uid)->update(['badge_id' => 0]);
        }
    }
}
