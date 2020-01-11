<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use think\Db;
use think\Model;

class StarBirthRank extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public static function change($uid, $starid, $hot)
    {
        $isDone = self::where('star_id', $starid)->where('user_id', $uid)->update(['count' => Db::raw('count+' . $hot)]);
        if (!$isDone) {
            self::create([
                'user_id' => $uid,
                'star_id' => $starid,
                'count' => $hot
            ]);
        }
    }

    /**排行 */
    public static function getRank($starid, $page, $size = 10)
    {
        $list = self::with(['user'])->where('star_id', $starid)->field('*,count as hot')->order('hot desc')->page($page, $size)->select();

        foreach ($list as &$value) {
            $starid = UserStar::getStarId($value['user_id']);
            $value['starname'] = Star::where('id', $starid)->value('name');
        }

        return $list;
    }

    /**给生日明星粉丝上周贡献前100名52000鲜花 */
    public static function grantBirthAward()
    {
        $starids = Star::where('birthday', (int) date('md'))->column('id');
        if ($starids) {
            $uids = [];
            foreach ($starids as $starid) {
                $uids = array_merge($uids, UserStar::where('star_id', $starid)->order('lastweek_count desc')->limit(100)->column('user_id'));
            }
            $userService = new User;
            foreach ($uids as $uid) {
                $userService->change($uid, ['flower' => 52000], '生日福利~');
            }
        }
    }
}
