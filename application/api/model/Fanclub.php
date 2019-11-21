<?php

namespace app\api\model;

use app\api\controller\v1\FansClub;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class Fanclub extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,head_img_s,name');
    }

    /**加入粉丝团 */
    public static function joinFanclub($uid, $f_id)
    {
        if (UserStar::getStarId($uid) != Fanclub::where('id', $f_id)->value('star_id')) {
            Common::res(['code' => 1, 'msg' => '不能加入所属其他爱豆的粉丝团']);
        }

        $isExist = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($isExist) Common::res(['code' => 1, 'msg' => '你已加入了一个粉丝团']);

        self::where('id', $f_id)->update([
            'mem_count' => Db::raw('mem_count+1')
        ]);

        FanclubUser::create([
            'user_id' => $uid,
            'fanclub_id' => $f_id,
        ]);
    }

    /**退出粉丝团 */
    public static function exitFanclub($uid)
    {
        $delete_time = Db::name('fanclub_user')->where('user_id', $uid)->order('delete_time desc')->value('delete_time');
        if (strtotime($delete_time) > time() - 3600 * 24 * 3) {
            Common::res(['code' => 1, 'msg' => '三天之内不能再次退出粉丝团']);
        }

        $curFid = FanclubUser::where('user_id', $uid)->value('fanclub_id');

        if ($curFid != 0) {
            Db::startTrans();
            try {
                // 用户退出
                FanclubUser::destroy(['user_id' => $uid]);

                self::where('id', $curFid)->update([
                    'mem_count' => Db::raw('mem_count-1')
                ]);

                // 团长退出
                $leader = FanclubUser::isLeader($uid);
                if ($leader) {
                    $user_id = FanclubUser::where(['fanclub_id' => $curFid])->order('thisweek_count desc')->value('user_id');
                    if ($user_id === null) {
                        // 销毁粉丝团
                        self::destroy(['user_id' => $uid]);
                    } else {
                        // 转交团长
                        self::where('id', $curFid)->update(['user_id' => $user_id]);
                    }
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }

    /**粉丝团贡献度增加 */
    public static function change($uid, $hot)
    {
        $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($fid != 0) {
            self::where('id', $fid)->update([
                'week_count' => Db::raw('week_count+' . $hot),
                'month_count' => Db::raw('month_count+' . $hot)
            ]);

            FanclubUser::where('user_id', $uid)->update(['thisweek_count' => Db::raw('thisweek_count+' . $hot)]);
        }
    }
}
