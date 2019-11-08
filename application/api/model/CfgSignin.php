<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class CfgSignin extends Base
{
    //

    /**群红包 */
    public static function hongBao($awardType, $uid)
    {
        switch ($awardType) {
            case 1:
                // ------ 管理员发奖励
                // 每个人只能领一次管理员发的红包
                // 领了管理员红包表示已加群
                $isJoin = UserExt::where('user_id', $uid)->value('is_join_wxgroup');
                if ($isJoin == 1) {
                    Common::res(['data' => [
                        'error' => 1,
                        'title' => '你已领取过该红包',
                    ]]);
                }

                $award = Cfg::getCfg('hongbao')[$awardType];
                Db::startTrans();
                try {
                    (new User)->change($uid, $award, [
                        'type' => 27
                    ]);

                    UserExt::where('user_id', $uid)->update(['is_join_wxgroup' => 1]);
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    Common::res(['code' => 400, 'msg' => $e->getMessage()]);
                }

                Common::res([
                    'data' => [
                        'error' => 0,
                        'title' => '领取成功！',
                        'award' => $award,
                        'userExt' => ['is_join_wxgroup' => 1]
                    ]
                ]);

                break;
            case 2:
                // ------ 团长发红包
                $isJoin = UserExt::where('user_id', $uid)->value('is_join_wxgroup');

                $award = [
                    'coin' => 1000,
                    'stone' => 1
                ];
                Db::startTrans();
                try {
                    (new User)->change($uid, $award, [
                        'type' => 27
                    ]);

                    UserExt::where('user_id', $uid)->update(['is_join_wxgroup' => 1]);
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    Common::res(['code' => 400, 'msg' => $e->getMessage()]);
                }

                Common::res([
                    'data' => [
                        'error' => 0,
                        'title' => '领取成功！',
                        'award' => $award
                    ]
                ]);

                break;

            default:
                # code...
                break;
        }
    }
}
