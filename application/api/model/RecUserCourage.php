<?php


namespace app\api\model;


use app\base\service\Common;
use think\Db;

class RecUserCourage extends \app\base\model\Base
{
    const NORMAL = 'normal';
    const SUPER_SECRET = 'super_secret';
    const OTHER = 'other';

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,nickname,avatarurl');
    }

    public static function checkTime()
    {
        $time1 = date('Y-m-d') . ' 00:00:00';
        $time2 = date('Y-m-d') . ' 12:00:00';
        $time3 = date('Y-m-d') . ' 18:00:00';
        if (date('Y-m-d H:i:s') >= $time1 && date('Y-m-d H:i:s') < $time2) {
            $date = $time1;
        } elseif (date('Y-m-d H:i:s') >= $time2 && date('Y-m-d H:i:s') < $time3) {
            $date = $time2;
        } else {
            $date = $time3;
        }
        return $date;
    }


    public static function getReward($uid, $type)
    {
        $userStarInfo = UserStar::where('user_id',$uid)->find();
        if (!$userStarInfo) Common::res(['code' => 1, 'msg' => '请先选择加入一个圈子']);
        $count = 0;
        $pet_adventure = Cfg::getCfg('pet_adventure');
        $star_reward = $pet_adventure['reward'];
        $userAnimal = UserAnimal::with('Animal')->where('user_id', $uid)->field('id,animal_id,level')->select();
        foreach ($userAnimal as $value) {
            if (!$value['animal']) continue;
            $adventure_type = $value['animal']['adventure_type'];
            if ($type == self::OTHER) {
                if ($adventure_type == 'secret' || $adventure_type == 'season' || $adventure_type == 'star_secret') {
                    $addnum = $star_reward[$adventure_type][$value['level'] - 1];
                    $count += $addnum;
                }
            } else {
                if ($adventure_type == $type) {
                    $addnum = $star_reward[$adventure_type][$value['level'] - 1];
                    $count += $addnum;
                }
            }

        }
        if ($count <= 0) Common::res(['code' => 1, 'msg' => '无冒险勇气领取']);

        $date_time = self::checkTime();

        Db::startTrans();
        try {
            $isDone = self::where('user_id',$uid)->where('type',$type)->where('date_time',$date_time)->update([
                'update_time' => date('Y-m-d H:i:s')
            ]);
            if ($isDone) Common::res(['code' => 1, 'msg' => '已领取']);

            $isDone1 = UserStar::where('user_id', $uid)->update([
                'courage' => Db::raw('courage+' . $count),
                'courage_last_time' => time()
            ]);
            if (!$isDone1) Common::res(['code' => 1, 'msg' => '领取失败']);
            $star_id = UserStar::getStarId($uid);
            $isDone2 = StarRank::where('star_id', $star_id)->update([
                'courage' => Db::raw('courage+' . $count),
                'courage_last_time' => time()
            ]);
            if (!$isDone2) Common::res(['code' => 1, 'msg' => '领取失败']);

            self::create([
                'user_id' => $uid,
                'type' => $type,
                'count' => $count,
                'date_time' => $date_time
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
        return $count;
    }

    public static function getList($uid, $page, $size)
    {
        $logList = self::where('user_id', $uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);
        foreach ($logList as &$value){
            $value['content'] = '';
            if($value['type'] == self::OTHER){
                $value['content'] = '带上萌宠冒险获得勇气';
            }else if($value['type'] == self::NORMAL){
                $value['content'] = '带上十二生肖冒险获得勇气';
            }else if($value['type'] == self::SUPER_SECRET){
                $value['content'] = '带上灵宠冒险获得勇气';
            }
        }

        return $logList;
    }

}