<?php
namespace app\api\controller\v1;

use app\api\model\ActiveDragonBoatFestivalFanclub;
use app\api\model\Cfg;
use app\api\model\CfgActive;
use app\api\model\CfgLottery;
use app\api\model\OpenRank;
use app\api\model\RecPanaceaTask;
use app\api\model\RecTaskactivity618;
use app\api\model\RecUserPaid;
use app\api\model\RecWealActivityTask;
use app\api\model\UserAchievementHeal;
use app\api\model\UserManor;
use app\base\controller\Base;
use app\api\model\StarRank;
use Exception;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;
use app\api\model\UserCurrency;
use app\api\model\Fanclub;
use app\api\model\FanclubUser;
use app\api\model\Lock;
use app\api\model\PayGoods;
use app\api\model\PkUser;
use app\api\model\Rec;
use app\api\model\RecTask;
use app\api\model\StarBirthRank;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\service\User;
use app\base\service\Common;
use app\api\model\RecTaskfanclub;
use app\api\model\StarRankHistoryExt;
use app\api\model\Family;
use app\api\model\FamilyUser;
use app\base\model\Appinfo;
use think\Log;
use Throwable;

class AutoRun extends Base
{

    public function index()
    {
        echo $this->minuteHandle() . '</br>';
        echo $this->dayHandle() . '</br>';
        echo $this->weekHandle() . '</br>';
        echo $this->monthHandle() . '</br>';
    }

    /**
     * 每分钟执行
     */
    public function minuteHandle()
    {
        $lock = Lock::getVal('minute_end');


        if (time()-60 < strtotime($lock['time'])) {
            return '本分钟已执行过';
        }

        // lock
        Lock::setVal('minute_end', 1);

        Db::startTrans();
        try {
            $time=time()-60;
            $activeDragonBoatFestivalFanclubs = ActiveDragonBoatFestivalFanclub::where('active_time','>',$time)->field('id,fanclub_id,total_count,before_count')->select();
            foreach ($activeDragonBoatFestivalFanclubs as $value){
                $total_count = FanclubUser::where('fanclub_id',$value['fanclub_id'])->limit(100)->order('dragon_boat_festival_hot desc,create_time asc')->sum('dragon_boat_festival_hot');
                if(!$value['before_count']){
                    $value['before_count']=json_encode([]);
                }
                $before_count=json_decode($value['before_count'],true);
                array_push($before_count,$total_count);
                ActiveDragonBoatFestivalFanclub::where('id',$value['id'])->update([
                    'total_count'=>$total_count,
                    'before_count'=>json_encode($before_count),
                ]);
            }

            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }

        // lock
        Lock::setVal('minute_end', 0);

        return '本分钟执行完毕';
    }

    /**
     * 每日执行
     */
    public function dayHandle()
    {
        $lock = Lock::getVal('day_end');
        if (date('md', time()) == date('md', strtotime($lock['time']))) {
            return '本日已执行过';
        }
        // lock
        Lock::setVal('day_end', 1);
        
        Db::startTrans();
        try {

            //清空抽奖记录表
            Db::execute('truncate table f_rec_lottery;');

            // 重置公众号可用状态
            Appinfo::where('1=1')->update([
                'status' => 0
            ]);
            
            // 转存历史排名_活动期间
            $rankListFlower = StarRank::getRankList(1, 10, 'day_hot_flower');
            StarRankHistoryExt::create([
                'date' => date('Ymd', time() - 3600),
                'value' => json_encode($rankListFlower, JSON_UNESCAPED_UNICODE),
                'field' => 'day_hot_flower'
            ]);
            
            // 重置明星人气
            StarRank::where('1=1')->update([
                'day_hot_flower' => 1000
            ]);
            // 用户日贡献清零
            UserStar::settleFlower ();
            UserStar::settleCount ();
            UserAchievementHeal::cleanDayInvite();
            UserStar::where('1=1')->update([
                'lastday_count' => Db::raw('thisday_count'),
                'thisday_count' => 0,
                'yesterday_flower' => Db::raw('day_flower'),
                'yesterday_flower_time' => Db::raw('update_time'),
                'day_flower' => 0,
            ]);
            // 每日任务进度清零
            RecTask::where('task_type', 1)->update([
                'done_times' => 0,
                'is_settle' => 0
            ]);
            // 限量商品重置数量
            PayGoods::where('remain', 'not null')->update([
                'remain' => 100
            ]);
            PayGoods::where('id', 12)->update([
                'remain' => 300
            ]);
            
            // 农场挖金豆技能使用次数清零
            UserSprite::where('1=1')->update([
                'skill_two_times' => 0,
                'skill_two_offset' => 0
            ]);
            // 重置 抽奖次数 每日点赞次数 每日召唤宠物数
            UserExt::where('1=1')->update([
                'lottery_count' => 0,
                'lottery_times' => 0,
                'thisday_like' => 0,
                'animal_lottery' => 0
            ]);
            
            // 家族贡献重置
            Family::where('1=1')->update([
                'lastday_count' => Db::raw('day_count'),
                'day_count' => 0,
            ]);
            
            FamilyUser::where('1=1')->update([
                'lastday_count' => Db::raw('day_count'),
                'day_count' => 0,
            ]);

            $massDate = date('YmdH');
            FanclubUser::where('day_mass_times', '>', 0)->update([
                'day_mass_times' => Db::raw('if(`mass_time` = '.$massDate.', 1, 0)'),
            ]);

            RecTaskactivity618::where('task_id','in',[4,8,9])->update([
                'done_times'=>0,
                'is_settle_times'=>0,
            ]);
            
            // 给今日生日明星粉丝上周贡献前100名52000鲜花
            StarBirthRank::grantBirthAward();
            // 周末抽奖双倍
            CfgLottery::doubleAward();

            // 清理每日任务
            RecWealActivityTask::cleanDay ();
            RecPanaceaTask::cleanDay();

            // 清除用户充值礼包记录
            RecUserPaid::where('count', '>', 0)
                ->update(['count' => 0,'is_settle' => 0]);

            // 每日庄园结算
            UserManor::where('1=1')->update([
                'day_count' => 0,
                'day_steal' => 0,
                'day_lottery_times' => 0,
                'day_lottery_box' => '[]',
            ]);

            // pk表清除
            PkUser::clearPk();

            // 开屏备选的清理
            // 先注释，活动开启后再查询 2020年10月08日18:36:47
//            if (Cfg::checkActiveByPathInBtnGroup (Cfg::OPEN_RANK_PATH)) {
//                \app\api\model\Open::overSettle();
//
//                \app\api\model\Open::where('hot', '>', 0)
//                    ->where ('type', \app\api\model\Open::SOLDIER81)
//                    ->update(['hot' => 0]);
//
//                OpenRank::where('count', '>', 0)->delete(true);
//            }
//
//            $status = Cfg::checkInviteAssistTime ();
//            if ($status) {
//                \app\api\model\UserInvite::cleanDayInvite ();
//            }
            Db::commit();
        } catch (Throwable $e) {
            Db::rollBack();
            Log::error('day autorun fail');
            Log::error('code:' . $e->getCode());
            Log::error('message:' . $e->getMessage());
            Log::error('file:' . $e->getFile());
            Log::error('line:' . $e->getLine());
            Log::error('trace:');
            Log::error(json_encode($e->getTrace()));
            return 'rollBack:' . $e->getMessage();
        }
        
        // lock
        Lock::setVal('day_end', 0);
        
        return '本日执行完毕';
    }

    /**
     * 每周执行
     */
    public function weekHandle()
    {
        $lock = Lock::getVal('week_end');
        if (date('oW', time()) == date('oW', strtotime($lock['time']))) {
            return '本周已执行过';
        }
        
        // lock
        Lock::setVal('week_end', 1);
        
        Db::startTrans();
        try {
            // 用户金豆每周清零
            UserCurrency::where('1=1')->update([
                'coin' => 0,
                'update_time' => Db::raw('update_time'),
            ]);
            
            // 用户周贡献清零
            UserStar::where('1=1')->update([
                'weekend_total_count' => Db::raw('total_count'),//每周日统计的总贡献（活动时要用）
                'lastweek_count' => Db::raw('thisweek_count'),
                'thisweek_count' => 0,
                'achievement_week_count' => 0,
                'update_time' => Db::raw('update_time'),
            ]);
            
            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'week_hot');
            StarRankHistory::create([
                'date' => date('oW', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'week_hot'
            ]);
            
            // 转存历史排名_活动期间
            $rankListFlower = StarRank::getRankList(1, 10, 'week_hot_flower');
            StarRankHistoryExt::create([
                'date' => date('oW', time() - 3600),
                'value' => json_encode($rankListFlower, JSON_UNESCAPED_UNICODE),
                'field' => 'week_hot_flower'
            ]);
            
            // 重置明星人气
            StarRank::where('1=1')->update([
                'week_hot' => 10000,
                'week_hot_flower' => 10000
            ]);
            
            // 重置离线收益结算时间
            UserSprite::where('1=1')->update([
                'settle_time' => time()
            ]);
            
            // 前三
            // $topThreeAward = [290000, 190000, 90000];
            // $topThreeIds = array_slice(array_column($rankList, 'star_id'), 0, 3);
            // foreach ($topThreeAward as $key => $value) {
            // StarRank::where(['star_id' => $topThreeIds[$key]])->update([
            // 'week_hot' => Db::raw('week_hot+' . $value),
            // 'month_hot' => Db::raw('month_hot+' . $value),
            // ]);
            // }
            
            // 粉丝团贡献重置
            Fanclub::where('1=1')->update([
                'lastweek_hot' => Db::raw('week_hot'),
                'lastweek_count' => Db::raw('week_count'),
                'lastweekmem_count' => Db::raw('weekmem_count'),
                'lastweekbox_count' => Db::raw('weekbox_count'),
                'week_hot' => 0,
                'week_count' => 0,
                'weekmem_count' => 0,
                'weekbox_count' => 0,
            ]);

            FanclubUser::where('1=1')->update([
                'lastweek_hot' => Db::raw('week_hot'),
                'lastweek_count' => Db::raw('week_count'),
                'lastweekmem_count' => Db::raw('weekmem_count'),
                'lastweekbox_count' => Db::raw('weekbox_count'),
                'week_hot' => 0,
                'week_count' => 0,
                'weekmem_count' => 0,
                'weekbox_count' => 0,
            ]);
            
            RecTaskfanclub::where('1=1')->update([
                'is_settle' => 0
            ]);
            
            
            // 家族贡献重置
            Family::where('1=1')->update([
                'lastweek_count' => Db::raw('week_count'),
                'week_count' => 0,
            ]);
            
            FamilyUser::where('1=1')->update([
                'lastweek_count' => Db::raw('week_count'),
                'week_count' => 0,
            ]);

            // 每周庄园结算
            UserManor::where('1=1')->update([
                'week_count' => 0,
            ]);
            
            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }
        // 解锁
        Lock::setVal('week_end', 0);
        return '本周执行完毕';
    }

    /**
     * 每月执行
     */
    public function monthHandle()
    {
        $lock = Lock::getVal('month_end');
        if (date('Ym', time()) == date('Ym', strtotime($lock['time']))) {
            return '本月已执行过';
        }
        
        Lock::setVal('month_end', 1);
        
        Db::startTrans();
        try {
            // 应援打卡开启，上个月打卡日志保留一个月
            $preShowId = Db::name('cfg_active')->where('delete_time','NOT NULL')->column('id');
            CfgActive::destroy(function ($query){
                $query -> where('1=1');
            });
            $active_date = json_encode([date('Y-m-d'),date('Y-m-d',strtotime("+1 month"))]);
            Db::name('cfg_active')->whereIn('id',$preShowId)->update(['active_date'=>$active_date,'delete_time'=>NULL]);
            Db::name('rec_active')->whereIn('active_id',$preShowId)->delete();

            // 用户月贡献清零
            UserStar::where('1=1')->update([
                'lastmonth_count' => Db::raw('thismonth_count'),
                'thismonth_count' => 0,
                'achievement_month_count' => 0,
                'update_time' => Db::raw('update_time'),
            ]);
            
            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'month_hot_flower');
            StarRankHistory::create([
                'date' => date('Ym', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'month_hot_flower'
            ]);
            $rankList = StarRank::getRankList(1, 10, 'month_hot_coin');
            StarRankHistory::create([
                'date' => date('Ym', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'month_hot_coin'
            ]);
            
            // 重置明星人气
            StarRank::where('1=1')->update([
                'month_hot' => 10000,
                'month_hot_flower' => 10000,
                'month_hot_coin' => 10000
            ]);
            
            // 后援会贡献重置
            Fanclub::where('1=1')->update([
                'lastmonth_count' => Db::raw('month_count'),
                'month_count' => 0
            ]);
            
            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }
        
        Lock::setVal('month_end', 0);
        return '本月执行完毕';
    }

    public function clearDb()
    {
        $day = input('day', 10);
        $count = input('count', 100);
        
        Rec::clear($day, $count);
        RecTask::clear($day, $count);
        echo 'done';
    }

    /**
     * 微博热搜抓取
     */
    public function hotsearchFetch()
    {}

    public function pk_settle()
    {
        $list = Db::name('pk_settle_i')->limit(3)->select();
        $userService = new User();
        foreach ($list as $value) {
            Db::startTrans();
            try {
                $isDone = Db::name('pk_settle_i')->where('id', $value['id'])->delete();
                if ($isDone) {
                    $awards = json_decode($value['awards'], true);
                    $uids = json_decode($value['uids'], true);
                    
                    foreach ($uids as $uid) {
                        $userService->change($uid, [
                            'coin' => $awards['coin'],
                            'flower' => $awards['flower'],
                            'stone' => $awards['stone']
                        ], '团战奖励');
                    }
                }
                
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                Common::res([
                    'code' => 400,
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }
}
