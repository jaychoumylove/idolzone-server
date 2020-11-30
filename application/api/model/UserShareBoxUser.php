<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class UserShareBoxUser extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public static function getLog($uid, $page, $size)
    {
        $logList = self::with('user')->where('user_id',$uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);

        foreach ($logList as &$value) {
            $value['content'] ='打开有福同享金豆宝箱获得';
        }
        return $logList;
    }

    public static function open($uid, $box_id)
    {

        $isExist = self::where('box_id', $box_id)->where('user_id', $uid)->update([
            'update_time' => date('Y-m-d H:i:s')
        ]);
        if ($isExist) return false;

        //不在一个圈子
        $sendUserId = UserShareBox::where('id', $box_id)->value('user_id');
        $sendStarId = UserStar::getStarId($sendUserId);
        $getStarId = UserStar::getStarId($uid);
        if ($sendStarId != $getStarId) Common::res(['code' => 1, 'msg' => '不在同一个偶像圈']);

        if($sendUserId!=$uid){
            $isFriend1 = UserManorFriends::where('user_id', $uid)->where('friend_id', $sendUserId)->count();
            $isFriend2 = UserManorFriends::where('friend_id', $uid)->where('user_id', $sendUserId)->count();
            if(!$isFriend1 && !$isFriend2) Common::res(['code' => 1, 'msg' => '只有好友才能开启']);
        }

        // 宝箱信息
        $boxInfo = UserShareBox::where('id', $box_id)->find();

        // 剩余个数
        $remainCount = $boxInfo['remaining_people'];
        if ($remainCount <= 0) return false;

        // 总奖金
        $totalSum = $boxInfo['coin'];
        $sum = (new UserShareBoxUser())->readMaster()->where('box_id', $box_id)->sum('count');
        // 剩余奖金
        $remainSum = $totalSum - $sum;
        if ($remainSum <= 0) return false;
        // 给予奖励数额
        $awardNum = self::getAward($remainSum, $remainCount);

        Db::startTrans();
        try {
            $isExist = self::where('box_id', $box_id)->where('user_id', $uid)->update([
                'delete_time' => date('Y-m-d H:i:s')
            ]);
            if ($isExist) {
                Db::rollback();
                return false;
            } else{
                $isDone = UserShareBox::where('id', $box_id)->update([
                    'remaining_people' => Db::raw('remaining_people-1'),
                ]);

                if ($isDone) {
                    self::create([
                        'box_id' => $box_id,
                        'user_id' => $uid,
                        'count' => $awardNum
                    ]);

                    (new UserService)->change($uid, ['coin' => $awardNum], '打开有福同享金豆宝箱');
                }
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return true;

    }

    public static function openself($uid, $boxInfo)
    {
        Db::startTrans();
        try {
            $isExist = self::where('box_id', $boxInfo['id'])->where('user_id', $uid)->update([
                'delete_time' => date('Y-m-d H:i:s')
            ]);
            if($isExist) Common::res(['code' => 1, 'msg' => '宝箱已领取，只能直接领取完整的宝箱']);

            $isDone = UserShareBox::where('id', $boxInfo['id'])->update([
                'remaining_people' => Db::raw('remaining_people-10'),
            ]);
            if(!$isDone) Common::res(['code' => 1, 'msg' => '宝箱已领取完了']);

            self::create([
                'box_id' => $boxInfo['id'],
                'user_id' => $uid,
                'count' => $boxInfo['coin']
            ]);
            (new UserService)->change($uid, ['coin' => $boxInfo['coin']], '直接领取友谊金豆宝箱');

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }


    /**
     * 获取一个宝箱的奖金金额
     * @param int $total 奖金
     * @param int $count 剩余个数
     */
    public static function getAward($total, $count)
    {
        if ($count == 1) {
            // 最后一个红包，奖金全部给TA
            $award = $total;
        } else {
            // 奖金额度 = 奖金 / 红包个数 * 随机0.50-1.49倍
            do {
                $award = round($total / $count * mt_rand(50, 149) / 100);
            } while ($award >= $total);
        }

        return $award;
    }

}
