<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class Fanclub extends Base
{
    /**明星后援会列表 */
    public static function getList($uid, $star_id, $status = 2)
    {
        $curFid = UserStar::where('user_id', $uid)->value('fanclub_id');

        $list = self::where('star_id', $star_id)->where('status', $status)->select();

        foreach ($list as &$value) {
            if ($curFid == $value['id']) {
                $value['join'] = true;
            }
        }
        return $list;
    }

    /**用户加入后援会 */
    public static function joinFanclub($uid, $f_id)
    {
        self::exitFanclub($uid);

        self::where('id', $f_id)->update([
            'mem_count' => Db::raw('mem_count+1')
        ]);

        UserStar::where('user_id', $uid)->update([
            'fanclub_id' => $f_id
        ]);
    }

    /**退出后援会 */
    public static function exitFanclub($uid)
    {
        $curFid = UserStar::where('user_id', $uid)->value('fanclub_id');

        if ($curFid != 0) {
            Db::startTrans();
            try {
                UserStar::where('user_id', $uid)->update([
                    'fanclub_id' => 0
                ]);

                self::where('id', $curFid)->update([
                    'mem_count' => Db::raw('mem_count-1')
                ]);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }

    /**后援会贡献度增加 */
    public static function change($uid, $hot)
    {
        $curFid = UserStar::where('user_id', $uid)->value('fanclub_id');
        if ($curFid != 0) {
            self::where('id', $curFid)->update([
                'week_count' => Db::raw('week_count+' . $hot),
                'month_count' => Db::raw('month_count+' . $hot)
            ]);
        }
    }
}
