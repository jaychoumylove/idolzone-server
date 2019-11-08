<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use think\Db;
use think\Model;

class Wxgroup extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    /**新增群 */
    public static function groupAdd($open_gid, $starid)
    {
        $isFind = self::where('open_gid', $open_gid)->where('star_id', $starid)->find();
        if (!$isFind) {
            $isFind = self::create([
                'open_gid' => $open_gid,
                'star_id' => $starid
            ]);
        }

        return $isFind['id'];
    }

    /**增加贡献度 */
    public static function userSendHot($uid, $hot)
    {
        // 打一次榜只给参与集结次数最多的群加贡献
        $gid = UserWxgroup::where('user_id', $uid)->order('mass_times desc,mass_join_at desc,id desc')->value('wxgroup_id');
        // 个人贡献
        UserWxgroup::where('user_id', $uid)->where('wxgroup_id', $gid)->update([
            'thisday_count' => Db::raw('thisday_count+' . $hot),
        ]);

        // 群贡献
        self::where('id', $gid)->update([
            'total_count' => Db::raw('total_count+' . $hot),
            'thisday_count' => Db::raw('thisday_count+' . $hot),
        ]);
    }

    // 每日重置数据
    public static function dayInit()
    {
        // 群贡献奖励
        $groupIds = self::where('1=1')->order('thisday_count desc')->limit(10)->column('id');
        $rebackCfg = Cfg::getCfg('groupmass')['reback'];

        foreach ($groupIds as $rank => $gid) {
            UserWxgroup::where('wxgroup_id', $gid)->update([
                'daycount_reback' => Db::raw('thisday_count*' . $rebackCfg[$rank])
            ]);
        }

        // 重置
        self::where('1=1')->update([
            'thisday_count' => 0,
        ]);
        UserWxgroup::where('1=1')->update([
            'thisday_count' => 0,
        ]);
    }
}
