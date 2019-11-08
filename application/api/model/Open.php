<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class Open extends Base
{
    //

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name');
    }

    /**获取开屏图 */
    public static function getRankList($page, $size, $sort)
    {
        $list = self::with('Star')->where('1=1')->order('hot desc,id desc')->page($page, $size)->select();
        return $list;
    }

    /**增加开屏图人气 */
    public static function addHot($id, $uid, $hot)
    {
        self::where('id', $id)->update(['hot' => Db::raw('hot+' . $hot)]);

        $isDone = OpenRank::where('user_id', $uid)->where('open_id', $id)->update(['count' => Db::raw('count+' . $hot)]);
        if (!$isDone) {
            $isDone = OpenRank::create([
                'user_id' => $uid,
                'open_id' => $id,
                'count' => $hot
            ]);
        }
    }

    public static function settle()
    {
        // 获取榜首数据
        $topOpen = self::where('1=1')->order('hot desc,id desc')->find();
        $starname = Star::where('id', $topOpen['star_id'])->value('name');
        $userRank = OpenRank::with('User')->where('open_id', $topOpen['id'])->limit(3)->order('count desc,id asc')->select();

        $res = OpenTop::create([
            'open_id' => $topOpen['id'],
            'open_img' => $topOpen['img_url'],
            'starname' => $starname,
            'user_rank' => json_encode($userRank),
            'date' => date("Ymd", strtotime("-1 day"))
        ]);

        // 清空
        self::where('1=1')->update(['hot' => 0]);
        OpenRank::where('1=1')->update(['count' => 0]);

        // if ($res) Common::res();
    }
}
