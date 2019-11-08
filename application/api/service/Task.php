<?php

namespace app\api\service;

use app\api\model\Cfg;
use app\api\model\RecTask;
use app\api\model\Task as TaskModel;
use think\Db;
use app\api\model\UserStar;
use app\base\service\Common;
use app\api\model\RecStarChart;
use app\api\model\Rec;
use app\api\model\RecPayOrder;
use app\api\model\RecWeibo;
use app\api\model\UserRelation;
use app\api\model\UserExt;
use app\api\model\RecItem;

class Task
{

    /**检查任务是否完成 */
    public function checkTask($uid, $type)
    {
        // 任务列表
        $taskList = TaskModel::where('type', $type)->order('sort asc')->select();
        // 任务完成进度
        $recTask = RecTask::getUserRec($uid, $type);

        foreach ($taskList as &$task) {
            // 任务状态 0未完成 1已完成 2已领取
            $task['status'] = 0;
            // 完成次数
            $task['doneTimes'] = 0;

            if (isset($recTask[$task['id']])) {
                $task['doneTimes'] = $recTask[$task['id']]['done_times'];

                if ($recTask[$task['id']]['is_settle']) {
                    // 已领取
                    $task['status'] = 2;
                } else if ($task['doneTimes'] >= $task['times']) {
                    // 已完成
                    $task['status'] = 1;
                }
            }
        }

        return $taskList;
    }

    // 特殊：每日签到
    public function daily(&$task, $uid)
    {
        if ($task['type'] == 1) {
            $times = RecTask::where(['user_id' => $uid, 'task_id' => $task['id']])->whereTime('create_time', '-7 day')->column('id');
            $task['coin'] += count($times) * 20;
        }
    }

    /**任务奖励领取 */
    public function settle($task_id, $uid)
    {
        $task = TaskModel::get($task_id);

        Db::startTrans();
        try {
            RecTask::settle($uid, $task_id);

            $update = [
                'coin' => $task['coin'],
                'flower' => $task['flower'],
                'stone' => $task['stone'],
                'trumpet' => $task['trumpet'],
            ];
            (new User())->change(
                $uid,
                $update,
                '完成任务'
            );

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $update;
    }

    public function saveWeibo($weiboUrl, $uid, $type)
    {
        $weiboUrlExist = RecWeibo::get(['md5' => md5($weiboUrl)]);
        if ($weiboUrlExist) Common::res(['code' => 1, 'msg' => '该链接已经提交使用']);

        if ($type == 0) {
            // 微博发帖
            // 匹配文本：爱豆圈子
            $matchText = Cfg::getCfg('appname');
            $task_id = 8;
        } else if ($type == 1) {
            // 微博转发
            // 匹配 被转发微博id
            $matchText = Cfg::getCfg('weibo_zhuanfa')['pick_text'];
            $task_id = 9;
        }

        $weiboContent = Common::request($weiboUrl);
        $isMatch = strpos($weiboContent, $matchText);
        if (!$isMatch) {
            Common::res(['code' => 1, 'msg' => '微博超话内容格式不正确']);
        } else {
            RecWeibo::create([
                'user_id' => $uid,
                'url' => $weiboUrl,
                'md5' => md5($weiboUrl),
                'type' => $type,
            ]);

            RecTask::addRec($uid, $task_id);
        }
    }

    /**获取标准微博url*/
    public function getWeiboUrl($weiboUrl)
    {
        $weiboAid = '';
        preg_match('/^https\:\/\/m\.weibo\.cn\/status\/(\d+)/i', $weiboUrl, $weiboAid1);
        preg_match('/^https\:\/\/m\.weibo\.cn\/\d+\/(\d+)/i', $weiboUrl, $weiboAid2);
        preg_match('/^https\:\/\/weibointl\.api\.weibo\.cn\/.+?weibo_id=(\d+)/i', $weiboUrl, $weiboAid3);
        if (isset($weiboAid1[1])) $weiboAid = $weiboAid1[1];
        elseif (isset($weiboAid2[1])) $weiboAid = $weiboAid2[1];
        elseif (isset($weiboAid3[1])) $weiboAid = $weiboAid3[1];

        if (!$weiboUrl || !$weiboAid) Common::res(['code' => 1, 'msg' => '微博链接不正确']);

        $weiboUrl = 'https://m.weibo.cn/status/' . $weiboAid;
        return $weiboUrl;
    }
}
