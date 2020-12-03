<?php


namespace app\api\model;

use app\base\service\Common;
use think\Db;

class RecUserHelpNumTask extends \app\base\model\Base
{

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,nickname,avatarurl');
    }

    public static function getReward($uid, $index)
    {
        $userStarInfo = UserStar::where('user_id', $uid)->find();
        if (!$userStarInfo) Common::res(['code' => 1, 'msg' => '请先选择加入一个圈子']);
        if ($userStarInfo['is_get_old_reward']==0) Common::res(['code' => 1, 'msg' => '网络原因，您尚未领取老用户福利，请刷新或重进页面']);
        $birthday_open = Cfg::getCfg('birthday_open');
        $info = $birthday_open['help_task_list'][$index] ? $birthday_open['help_task_list'][$index] : '';
        if (!$info) Common::res(['code' => 1, 'msg' => '不存在该任务']);

        if ($info['type'] == 'coin_flower') {
            $info['count'] = UserStar::where('user_id', $uid)->value('total_count');
        }
        if ($info['type'] == 'flower') {
            $info['count'] = UserStar::where('user_id', $uid)->value('total_flower');
        }
        if ($info['type'] == 'lukey_prop') {
            $info['count'] = UserProp::where('user_id', $uid)->where('prop_id', $info['prop_id'])->count();
        }

        $add_done_times = 0;
        $task = RecUserHelpNumTask::where('user_id', $uid)->where('index', $index)->find();
        if ($task) {
            if ($info['count'] != $task['done_times']) {
                $task['done_times'] = $info['count'];
            }
            $get_help_num = ($task['done_times'] - $task['is_settle_times']) / $info['need_num'];
            if ($get_help_num >= 1) {
                $count = floor($get_help_num) * $info['help_num'];
                $add_done_times = floor($get_help_num) * $info['need_num'];
            }
        } else {
            $get_help_num = $info['count'] / $info['need_num'];
            if ($get_help_num >= 1) {
                $count = floor($get_help_num) * $info['help_num'];
                $add_done_times = floor($get_help_num) * $info['need_num'];
            }
        }

        if (!$add_done_times) Common::res(['code' => 1, 'msg' => '无可领取助力值']);

        Db::startTrans();
        try {

            if ($task) {
                $isDone = self::where('user_id', $uid)->where('index', $index)
                    ->where('is_settle_times', $task['is_settle_times'])
                    ->update([
                        'done_times' => $info['count'],
                        'is_settle_times' => Db::raw('is_settle_times+' . $add_done_times),
                    ]);
                if (!$isDone) Common::res(['code' => 1, 'msg' => '无可领取助力值']);
            } else {
                self::create([
                    'index' => $index,
                    'user_id' => $uid,
                    'done_times' => $info['count'],
                    'is_settle_times' => Db::raw('is_settle_times+' . $add_done_times),
                ]);
            }

            $isDone1 = UserStar::where('user_id', $uid)->update([
                'help_num' => Db::raw('help_num+' . $count),
                'help_num_last_time' => time()
            ]);
            if (!$isDone1) Common::res(['code' => 1, 'msg' => '领取失败']);
            $star_id = UserStar::getStarId($uid);
            $isDone2 = StarRank::where('star_id', $star_id)->update([
                'help_num' => Db::raw('help_num+' . $count),
                'help_num_last_time' => time()
            ]);
            if (!$isDone2) Common::res(['code' => 1, 'msg' => '领取失败']);

            RecUserHelpNum::create([
                'user_id' => $uid,
                'type' => $info['type'],
                'count' => $count,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
        return $count;
    }

    public static function getOldReward($uid)
    {
        $user = UserStar::where('user_id', $uid)->where('is_get_old_reward', 0)->field('id,user_id,star_id,total_count,total_flower,is_get_old_reward')->find();
        if (!$user) return;

        $count2 = floor($user['total_flower'] / 10000) * 10;
        $prop_num = UserProp::where('user_id', $user['user_id'])->where('prop_id', 18)->count();
        $count3 = $prop_num * 2000;
        $count = $count2 + $count3;
        Db::startTrans();
        try {
            $isDone = UserStar::where('user_id', $uid)->where('is_get_old_reward', 0)->update([
                'is_get_old_reward' => 1
            ]);
            if(!$isDone) {
                Db::rollback();
                return;
            }
            RecUserHelpNumTask::where(['user_id' => $user['user_id']])->delete();

            RecUserHelpNumTask::create([
                'index' => 0,
                'user_id' => $user['user_id'],
                'done_times' => $user['total_count'],
                'is_settle_times' => $user['total_count'],
            ]);
            RecUserHelpNumTask::create([
                'index' => 1,
                'user_id' => $user['user_id'],
                'done_times' => $user['total_flower'],
                'is_settle_times' => $user['total_flower'],
            ]);
            RecUserHelpNumTask::create([
                'index' => 2,
                'user_id' => $user['user_id'],
                'done_times' => $prop_num,
                'is_settle_times' => $prop_num,
            ]);
            if ($count) {
                UserStar::where('user_id', $user['user_id'])->update([
                    'help_num' => Db::raw('help_num+' . $count),
                    'help_num_last_time' => time()
                ]);
                StarRank::where('star_id', $user['star_id'])->update([
                    'help_num' => Db::raw('help_num+' . $count),
                    'help_num_last_time' => time()
                ]);
                RecUserHelpNum::create([
                    'user_id' => $user['user_id'],
                    'type' => 'old',
                    'count' => $count,
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

    }

}