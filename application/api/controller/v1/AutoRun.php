<?php

namespace app\api\controller\v1;

use app\api\model\CfgLottery;
use app\base\controller\Base;
use app\api\model\StarRank;
use think\Db;
use app\api\model\StarRankHistory;
use app\api\model\UserStar;
use app\api\model\UserCurrency;
use app\api\model\OtherLock;
use think\Cache;
use app\api\model\Fanclub;
use app\api\model\Star;
use app\base\service\WxAPI;
use think\Log;
use app\api\model\Lock;
use app\api\model\Open;
use app\api\model\PayGoods;
use app\api\model\PkUser;
use app\api\model\Prop;
use app\api\model\Rec;
use app\api\model\RecCardHistory;
use app\api\model\RecTask;
use app\api\model\StarBirthRank;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\service\User;

class AutoRun extends Base
{
    public function index()
    {
        echo $this->dayHandle() . '</br>';
        echo $this->weekHandle() . '</br>';
        echo $this->monthHandle() . '</br>';
    }

    /**每日执行 */
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
            // 用户日贡献清零
            UserStar::where('1=1')->update([
                'thisday_count' => 0,
            ]);
            // 每日任务进度清零
            RecTask::where('task_type', 1)->update([
                'done_times' => 0,
                'is_settle' => 0,
            ]);
            // 限量商品重置数量
            PayGoods::where('remain', 'not null')->update(['remain' => 100]);
            // 农场挖金豆技能使用次数清零
            UserSprite::where('1=1')->update([
                'skill_two_times' => 0,
                'skill_two_offset' => 0
            ]);
            // 重置 抽奖次数 每日点赞次数
            UserExt::where('1=1')->update([
                'lottery_count' => 0,
                'lottery_times' => 0,
                'thisday_like' => 0,
            ]);
            // 给今日生日明星粉丝上周贡献前100名52000鲜花
            StarBirthRank::grantBirthAward();
            // 周末抽奖双倍
            CfgLottery::doubleAward();
            // pk表清除
            PkUser::clearPk();

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }

        // lock
        Lock::setVal('day_end', 0);

        return '本日执行完毕';
    }

    /**每周执行 */
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
            UserCurrency::where('1=1')->update(['coin' => 0]);

            // 用户周贡献清零
            UserStar::where('1=1')->update([
                'lastweek_count' => Db::raw('thisweek_count'),
                'thisweek_count' => 0,
            ]);

            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'week_hot');
            StarRankHistory::create([
                'date' => date('oW', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'week_hot',
            ]);

            // 重置明星人气
            StarRank::where('1=1')->update([
                'week_hot' => 10000,
            ]);

            // 前三
            // $topThreeAward = [290000, 190000, 90000];
            // $topThreeIds = array_slice(array_column($rankList, 'star_id'), 0, 3);
            // foreach ($topThreeAward as $key => $value) {
            //     StarRank::where(['star_id' => $topThreeIds[$key]])->update([
            //         'week_hot' => Db::raw('week_hot+' . $value),
            //         'month_hot' => Db::raw('month_hot+' . $value),
            //     ]);
            // }

            // 粉丝团贡献重置
            // Fanclub::where('1=1')->update([
            //     'lastweek_count' => Db::raw('week_count'),
            //     'week_count' => 0,
            // ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }
        // 解锁
        Lock::setVal('week_end', 0);
        return '本周执行完毕';
    }

    /**每月执行 */
    public function monthHandle()
    {
        $lock = Lock::getVal('month_end');
        if (date('Ym', time()) == date('Ym', strtotime($lock['time']))) {
            return '本月已执行过';
        }

        Lock::setVal('month_end', 1);

        Db::startTrans();
        try {
            // 用户月贡献清零
            UserStar::where('1=1')->update([
                'lastmonth_count' => Db::raw('thismonth_count'),
                'thismonth_count' => 0,
            ]);

            // 转存历史排名
            $rankList = StarRank::getRankList(1, 10, 'month_hot_flower');
            StarRankHistory::create([
                'date' => date('Ym', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'month_hot_flower',
            ]);
            $rankList = StarRank::getRankList(1, 10, 'month_hot_coin');
            StarRankHistory::create([
                'date' => date('Ym', time() - 3600),
                'value' => json_encode($rankList, JSON_UNESCAPED_UNICODE),
                'field' => 'month_hot_coin',
            ]);

            // 重置明星人气
            StarRank::where('1=1')->update([
                'month_hot' => 10000,
                'month_hot_flower' => 10000,
                'month_hot_coin' => 10000,
            ]);

            // 应援结算
            // RecCardHistory::settle();

            // 后援会贡献重置
            // Fanclub::where('1=1')->update([
            //     'lastmonth_count' => Db::raw('month_count'),
            //     'month_count' => 0
            // ]);

            Db::commit();
        } catch (\Exception $e) {
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

    /**微博热搜抓取 */
    public function hotsearchFetch()
    { }

    public function pk_settle()
    {
        $list = Db::name('pk_settle_i')->limit(3)->select();
        $userService = new User;
        foreach ($list as $value) {
            Db::startTrans();
            try {
                Db::name('pk_settle_i')->where('id', $value['id'])->delete();
                $awards = json_decode($value['awards'], true);
                $uids = json_decode($value['uids'], true);

                foreach ($uids as $uid) {
                    $userService->change($uid, [
                        'coin' => $awards['coin'],
                        'flower' => $awards['flower'],
                        'stone' => $awards['stone'],
                    ], '团战奖励');
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function temp()
    { }
}
