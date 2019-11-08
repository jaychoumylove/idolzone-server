<?php

namespace app\api\model;

use app\api\controller\v1\Pk;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class PkUser extends Base
{

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**检测团战是否可以报名 */
    public static function joinCheck($uid, $starid, $pkType, $pkTime)
    {
        // 是否被禁赛
        // $openTime = Db::name('pk_user_ban')->where('uid', $uid)->value('open_time');
        // if ($openTime && $openTime > time()) return ['status' => 1, 'msg' => '预计解封时间：' . date('Y-m-d H:m:s', $openTime)];

        // 每个用户同时只能报名一场团战
        $userExist = self::where(['pk_time' => $pkTime, 'user_id' => $uid])->value('id');
        if ($userExist) Common::res(['code' => 1, 'msg' => '你已报名其他场次']);

        // 每个明星每个场次最多100人
        $count = self::where(['pk_time' => $pkTime, 'pk_type' => $pkType, 'star_id' => $starid])->count('id');
        if ($count >= 100) Common::res(['code' => 1, 'msg' => '报名人数已满']);

        // $userLevel = Db::name('guild_user')->where(['uid' => $uid, 'star_id' => $starid])->value('level');
        // $minLevel = Db::name('pk_minlevel')->where(['star_id' => $starid])->value('min_level');
        // if ($userLevel < $minLevel) Common::res(['code' => 1, 'msg' => '粉丝等级过低，请提升等级后再来']);
    }

    /**新增团战报名 */
    public static function newUser($uid, $starid, $pkType, $pkTime)
    {
        self::create([
            'user_id' => $uid,
            'star_id' => $starid,
            'pk_time' => $pkTime,
            'pk_type' => $pkType
        ]);
    }

    public static function addHot($uid, $mid, $hot)
    {
        // 团战
        $rank = new Pk();
        $pk = $rank->getPkStatus();
        if ($pk['status'] == 2) {
            // 正在团战
            $pkTime = date('Y-m-d', time()) . ' ' . $pk['timeSpace']['start_time'] . ':00';
            $pkUser = Db::name('pk_user')->where(['pk_time' => $pkTime, 'uid' => $uid])->find();

            if ($pkUser) {
                // 该用户参加了本场团战
                Db::name('pk_user')->where(['id' => $pkUser['id']])->update([
                    'send_hot' => Db::raw('send_hot+' . $hot),
                ]);

                Db::name('pk_star')->where(['pk_time' => $pkTime, 'pk_type' => $pkUser['pk_type'], 'star_id' => $mid])->update([
                    'hot' => Db::raw('hot+' . $hot),
                ]);

                // 团战积分
                $pkRank = Db::name('pk_user_rank')->where(['uid' => $uid, 'mid' => $mid, 'week' => 0])->find();
                if ($pkRank) {
                    $pkUpdateData = [
                        'score' => Db::raw('score+' . $hot), // 10000贡献 = 1积分
                        'total_count' => Db::raw('total_count+' . $hot),

                        'last_pk_time' => $pkTime,
                        'last_pk_medal' => '',
                    ];
                    if ($pkRank['last_pk_time'] != $pkTime) {
                        $pkUpdateData['last_pk_count'] = $hot;
                    } else {
                        $pkUpdateData['last_pk_count'] = Db::raw('last_pk_count+' . $hot);
                    }
                    Db::name('pk_user_rank')->where(['uid' => $uid, 'mid' => $mid, 'week' => 0])->update($pkUpdateData);
                } else {
                    Db::name('pk_user_rank')->insert([
                        'uid' => $uid,
                        'mid' => $mid,
                        'm_name' => Star::where('id',  $mid)->value('name'),

                        'week' => 0,

                        'score' => $hot,
                        'total_count' => $hot,

                        'last_pk_time' => $pkTime,
                        'last_pk_medal' => '',
                        'last_pk_count' => $hot,
                    ]);
                }
            }
        }
    }
}
