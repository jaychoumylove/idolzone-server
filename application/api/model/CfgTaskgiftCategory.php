<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgTaskgiftCategory extends Base
{
    const ACHIEVEMENT_ID = 6;

    // 红点和title
    public static function getCategoryMore($uid)
    {
        $res['list'] = self::all(function ($query) {
            $query->where('end_time', 'NULL')->whereOR(['end_time' => ['>=', date('Y-m-d H:i:s')]]);
        });

        $res['all_title'] = ''; // 总标题
        $res['all_tips'] = 0; // 提醒红点

        foreach ($res['list'] as &$value) {
            $value['status'] = 0; // 是否显示
            $value['tips'] = 0; // 提醒红点
            $value['title'] = ''; // 标题内容

            switch ($value['id']) {
                case 1:
                    $value['title'] = '感恩有你，累计登陆7天领好礼';
                    break;
                case 2:
                    $userLevel = (int) CfgUserLevel::getLevel($uid);
                    $userGrown = (int) CfgUserLevel::where('level', $userLevel + 1)->value('total');
                    $value['title'] = '当前LV' . $userLevel;
                    if ($userGrown) {
                        $userNowHot = (int) UserStar::where('user_id', $uid)->value('total_count');
                        $value['title'] .= '，还差' . formatNumber($userGrown - $userNowHot) . '人气升至LV' . ($userLevel + 1);
                    }
                    break;
                case 3:
                    $userPayed = CfgTaskgift::userPayed($value['id'], $uid);
                    $userPayed['fee'] = $userPayed['fee'] ? $userPayed['fee'] : (int) $userPayed['fee'];
                    $userPayed['start_time'] = date('m月d', strtotime($userPayed['start_time']));
                    $userPayed['end_time'] = date('m月d', strtotime($userPayed['end_time']));
                    $value['title'] = $userPayed['start_time'] . '~' . $userPayed['end_time'] . ' 累计充值：' . round($userPayed['fee'], 2);
                    break;
                case 4:
                    $value['title'] = '关注爱豆圈子数据助手';
                    break;
                case self::ACHIEVEMENT_ID:
                    $value['title'] = '成就头饰领取后，有效期为3天';
                    $value['status'] = 1;
                    break;
            }

            // 获取子集任务的详情
            $resTask = CfgTaskgift::all(function ($query) use ($value) {
                $query->where('category_id', $value['id']);
            });

            foreach ($resTask as $k => $v) {
                $taskStatus = CfgTaskgift::getSettleStatu($value['id'], $v['id'], $uid)['status'];
                $value['status'] = (int) ($value['status'] == 1 || $taskStatus != 2);
                $value['tips'] = (int) ($value['tips'] == 1 || $taskStatus == 1);

                if ($res['all_title'] == '' && $value['status'] == 1) $res['all_title'] = $value['name'];
                $res['all_tips'] = (int) ($res['all_tips'] == 1 || $value['tips'] == 1);
            }
        }
        return $res;
    }
}
