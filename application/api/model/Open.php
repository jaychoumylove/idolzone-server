<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class Open extends Base
{
    const NORMAL = 'normal'; // 正常开屏活动
    const SOLDIER81 = '81soldier';// 81建军节开屏

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name');
    }

    public function uploader()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field ('id,nickname,avatarurl');
    }

    public function openRank()
    {
        return $this->hasMany ('OpenRank', 'open_id', 'id');
    }

    /**获取开屏图 */
    public static function getRankList($map = [], $page, $size, $sort)
    {
        $list = self::with(['Star', 'uploader', 'openRank' => function ($query) {
            $query->with('UserInfo')
                ->order([
                    'count' => 'desc',
                    'create_time' => 'asc'
                ]);
        }])
            ->where($map)
            ->order('hot desc,id asc')
            ->page($page, $size)
            ->select();
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

    public static function checkSoldier81()
    {
        // 7月26号24:00前有效
        return date ('Y-m-d') < '2020-07-27';
    }

    /**
     * 补充排名
     *
     * @param $hot
     * @param $id
     * @return mixed
     * @throws \think\Exception
     */
    public static function supportRank($hot, $id)
    {
        $rank = self::where('hot', '>', $hot)->count ();
        $sql = "SELECT id,hot,@curRank := @curRank + 1 AS rank FROM f_open p, (SELECT @curRank := 0) q where hot = $hot ORDER BY id asc";
        $sameRank = Db::query ($sql);
        $sameRankDict = array_column ($sameRank, 'rank', 'id');
        if (array_key_exists ($id, $sameRankDict)) {
            $rank += bcsub ($sameRankDict[$id], 1);
        }
        return $rank;
    }
}
