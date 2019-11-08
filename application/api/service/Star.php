<?php

namespace app\api\service;

use app\api\model\StarRank as StarRankModel;
use think\Db;
use app\api\model\UserStar;
use app\api\service\User as UserService;
use app\base\service\Common;
use app\api\model\Rec;
use app\api\model\Cfg;
use app\api\model\UserRelation;
use app\api\model\UserFather;
use app\api\model\OtherLock;
use think\Cache;
use app\api\model\UserExt;
use app\api\model\CfgItem;
use app\api\model\UserItem;
use app\api\model\User as UserModel;
use GatewayWorker\Lib\Gateway;
use app\api\model\RecItem;
use app\api\model\Fanclub;
use app\api\model\Lock;
use app\api\model\Open;
use app\api\model\PkUser;
use app\api\model\RecHour;
use app\api\model\RecTask;
use app\api\model\UserProp;
use app\api\model\Star as  StarModel;
use app\api\model\StarBirthRank;
use app\api\model\Wxgroup;

class Star
{

    public function getRank($score, $field)
    {
        return StarRankModel::where($field, 'GT', $score)->count() + 1;
    }

    /**今天是否是该明星生日 */
    public static function isTodayBrith($starid)
    {
        $birthday = StarModel::where('id', $starid)->value('birthday');
        return $birthday == date('md');
    }

    /**
     * 打榜
     * @param integer $starid 
     * @param integer $hot 人气 
     * @param integer $uid 
     * @param integer $type 打榜类型：1送金豆 2送鲜花
     */
    public function sendHot($starid, $hot, $uid, $type)
    {
        if (Lock::getVal('week_end')['value'] == 1) {
            Common::res(['code' => 1, 'msg' => '榜单结算中，请稍后再试！']);
        }

        if (!$starid) Common::res(['code' => 32, 'msg' => '请先加入一个圈子']);

        Db::startTrans();
        try {
            // 用户货币减少
            if ($type == 1) {
                $update = ['coin' => -$hot];
            } else if ($type == 2) {
                $update = ['flower' => -$hot];
            }

            (new UserService)->change($uid, $update, '为爱豆打榜');

            $myStarId = UserStar::getStarId($uid);
            if ($starid != $myStarId) {
                // 为其他爱豆打榜
                if ($type != 2) Common::res(['code' => 231, 'msg' => '请赠送鲜花']);
                StarBirthRank::change($uid, $starid, $hot);
            } else {
                // 用户贡献度增加
                UserStar::change($uid, $starid, $hot);
                // 占领封面小时榜贡献增加
                if ($type == 2) RecHour::change($uid, $hot, $starid);
                // 团战
                PkUser::addHot($uid, $starid, $hot);
                
                // 宝箱
            }
            
            RecTask::addRec($uid, [14, 15, 16, 17, 18], $hot);
            // 明星增加人气
            StarRankModel::change($starid, $hot, $type);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**今日偷取数额上限 */
    public static function stealCountLimit($uid)
    {
        $cfg = Cfg::getCfg('steal_count_limit');

        // 加上可偷金豆增加卡的上限
        $prop_id = 1;
        $count = 1 + UserProp::where([
            'user_id' => $uid,
            'prop_id' => $prop_id,
            'use_time' => ['>=', strtotime(date("Y-m-d"), time())] // 今日使用的
        ])->count('id');
        return $cfg * $count;
    }

    /**偷金豆 */
    public function steal($starid, $uid, $hot)
    {
        UserExt::checkSteal($uid);
        $userExt = UserExt::where(['user_id' => $uid])->field('steal_times,steal_count')->find();
        if ($userExt['steal_times'] >= Cfg::getCfg('steal_limit')) {
            Common::res(['code' => 1, 'msg' => '今日偷取次数已达上限']);
        }

        if ($userExt['steal_count'] >= self::stealCountLimit($uid)) {
            Common::res(['code' => 1, 'msg' => '今日偷取数额已达上限']);
        }

        Db::startTrans();
        try {
            StarRankModel::where(['star_id' => $starid])->update([
                'week_hot' => Db::raw('week_hot-' . $hot),
                'month_hot' => Db::raw('month_hot-' . $hot),
            ]);

            (new UserService())->change($uid, [
                'coin' => $hot,
            ]);

            UserExt::where(['user_id' => $uid])->update([
                'steal_times' => Db::raw('steal_times+1'),
                'steal_count' => Db::raw('steal_count+' . $hot),
                'steal_time' => time(),
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
