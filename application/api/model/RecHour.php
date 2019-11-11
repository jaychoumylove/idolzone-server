<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class RecHour extends Base
{
    public function User()
    {
        return $this->belongsTo('User')->field('id,nickname,avatarurl');
    }

    public function Star()
    {
        return $this->belongsTo('Star')->field('id,name,head_img_l');
    }

    /**小时榜第一 */
    public static function getTop()
    {
        $time = date('YmdH');

        return self::with('User,Star')->where('time', $time)->order('count desc,id asc')->find();
    }

    /**小时榜排名 */
    public static function getRankList(int $page = 1, $size = 10)
    {
        $time = date('YmdH');

        $list = self::with('User,Star')->where('time', $time)->order('count desc,id asc')->page($page, $size)->select();
        foreach ($list as &$value) {
            $value['user']['level'] = CfgUserLevel::getLevel($value['user']['id']);
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
        }

        return $list;
    }

    /**小时榜用户贡献增加 */
    public static function change($uid, $hot, $starid)
    {
        $time = date('YmdH');

        $isDone = self::where('user_id', $uid)->where('time', $time)->update(['count' => Db::raw('count+' . $hot)]);
        if (!$isDone) {
            self::create([
                'user_id' => $uid,
                'star_id' => $starid,
                'count' => $hot,
                'time' => $time
            ]);
        }
    }
}
