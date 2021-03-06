<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class CfgTaskgift extends Base
{

    public static function listHandle($cid, $list, $uid)
    {
        foreach ($list as &$value) {
            $value['awards'] = json_decode($value['awards'], true);
            $isAchievement = (int)$cid == CfgTaskgiftCategory::ACHIEVEMENT_ID;
            $data = $isAchievement ? self::getAchievementStatus ($value['awards'], $uid): self::getSettleStatu($cid, $value['id'], $uid);
            $value['over'] = $data['status'];
            $value['btn_text'] = $data['btn_text'];
            $value['name_addon'] = $data['name_addon'];
            if ($isAchievement) $value['img'] = $data['img'];
            if ($isAchievement) $value['num'] = $data['num'];
        }
        return $list;
    }

    public static function getAchievementStatus($reward, $user_id)
    {
        $name_addon = '';

        $key = $reward['achievement'];
        $res = UserAchievementHeal::checkStatus ($user_id, $key);
        $btnTextMapByKey = [
            UserAchievementHeal::FLOWER => '送鲜花',
            UserAchievementHeal::FLOWER_TIME => '去邀请',
            UserAchievementHeal::PK => '去PK',
            UserAchievementHeal::NEW_GUY => '去打榜',
        ];
        $status = (int)$res['status'];
        if ((int) $status) {
            $btn_text =  (int)$status ? '领取': '未达成';
        } else {
            $btn_text = array_key_exists ($key, $btnTextMapByKey) ? $btnTextMapByKey[$key]: "未达成";
        }
        $num = (int)$res['num'];

        $reward = CfgHeadwear::where('key', UserAchievementHeal::$typeMap[$key])->find ();

        $img = $reward['img'];

        return compact ('status', 'btn_text', 'name_addon', 'img', 'num');
    }

    public static function getSettleStatu($cid, $task_id, $uid)
    {
        $cTitle = CfgTaskgiftCategory::where('id', $cid)->value('name');
        //0.未达标，1.未领取，2.已完成
        $res = RecTaskgift::where([
            'user_id' => $uid,
            'task_id' => $task_id
        ])->value('id');
        if ($res) return ['title' => $cTitle, 'btn_text' => '已完成', 'name_addon' => '', 'status' => 2];

        switch ($cid) {
            case 1:
                $res = RecTaskgift::where([
                    'user_id' => $uid,
                    'cid' => $cid
                ])->order('update_time asc')->column('update_time');
                $status = ($task_id <= count($res) + 1);
                if ($status && isset($res[count($res) - 1])) {
                    $status = date('Ymd', strtotime($res[count($res) - 1])) != date('Ymd');
                }
                $btn_text = $status ? '领取' : '明天继续';
                break;

            case 2:
                $userLevel = (int) CfgUserLevel::getLevel($uid);
                $status = !($userLevel < self::where('id', $task_id)->value('count'));
                $btn_text = $status ? '领取' : '贡献人气';
                break;

            case 3:
                $userPayed = self::userPayed($cid, $uid)['fee'];
                $userPayed = $userPayed ? $userPayed : 0;
                $status = $userPayed >= self::where('id', $task_id)->value('count');
                $btn_text = $status ? '领取' : '去充值';
                $name_addon = $status ? '' : round($userPayed, 2) . '/' . self::where('id', $task_id)->value('count');
                break;

            case 4:
                $complete = GzhUser::where('gzh_appid', 'wx3507654fa8d00974')->where('user_id', $uid)->count();
                $status = $complete;
                $btn_text = $status ? '领取' : '去关注';
                break;

            case 5:
                $app = User::where('id', $uid)->value('openid_app');
                $status = $app ? 1 : 0;
                $btn_text = $status ? '领取' : '去下载';
                break;
        }
        if (!isset($status)) $status = 0;
        if (!isset($btn_text)) $btn_text = '未完成';
        if (!isset($name_addon)) $name_addon = '';
        return ['title' => $cTitle, 'btn_text' => $btn_text, 'name_addon' => $name_addon, 'status' => (int) $status];
    }

    //活动期间用户累计支付
    public static function userPayed($cid, $uid)
    {
        $data = CfgTaskgiftCategory::where('id', $cid)->field('start_time,end_time')->find();
        $where['create_time'] = [
            'between',
            [
                $data['start_time'],
                $data['end_time']
            ]
        ];
        $where['pay_time'] = ['NEQ', 'NULL'];
        $data['fee'] = RecPayOrder::where('tar_user_id', $uid)->where($where)->value('sum(total_fee)');

        return $data;
    }
}
