<?php

namespace app\api\model;

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
        $fanclubUser = Db::name('fanclub_user')->where('user_id', $uid)->find();
        if (strtotime($fanclubUser['delete_time']) > time() - 3600 * 24 * 3) {
            Common::res(['code' => 1, 'msg' => '三天之内不能再次退出粉丝团']);
        }

        $fid = $fanclubUser['fanclub_id'];

        Db::startTrans();
        try {
            FanclubUser::destroy(['user_id' => $uid]);

            self::where('id', $fid)->update([
                'mem_count' => Db::raw('mem_count-1')
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
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
