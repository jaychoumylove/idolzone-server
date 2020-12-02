<?php

namespace app\api\model;

use app\base\model\Base;
use Exception;
use think\Db;
use app\base\service\Common;
use GatewayWorker\Lib\Gateway;
use app\base\service\WxAPI;

class RecStarChart extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }
    
    public static function getLeastChart($starid,$type=0)
    {
        $list = self::with(['User' => [
            'UserStar' => function ($query) {
                $query->field('user_id,total_count,captain');
            }
        ]])->where(['star_id' => $starid,'type' => $type])->order('id desc')->limit(10)->select();


        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
        foreach ($list as &$value) {
            // 粉丝等级
            $value['user']['level'] = CfgUserLevel::getLevel($value['user_id']);
            // 头饰
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
            // 粉丝团团长
            $value['user']['isLeader'] = FanclubUser::isLeader($value['user_id']);
            //徽章
            $value['user']['userBadge'] = BadgeUser::getUse($value['user_id']);
        }

        return array_reverse($list);
    }

    /**
     * 敏感词汇校验
     * @return [0] 包含敏感词汇返回 true
     *         [1] 格式化后的内容
     */
    public static function verifyWord($text)
    {
       (new WxAPI())->msgCheck($text);
    }
    
    /**留言 */
    public static function sendMsg($uid, $starid, $content)
    {
        // 校验
        self::verifyWord($content);

        $currentDate = date('Y-m-d H:i:s');
        $userStar = UserStar::getStarId($uid);
        $star = Star::get($userStar);
        if ($star['report_open']) {
            if($star['report_open'] <= $currentDate) {
//            解封
                Star::where('id', $starid)->update([
                    'report_num' => 0,
                    'report_open' => null,
                    'report_user' => '[]',
                    'report_time' => null,
                ]);
                $star['report_open'] = null;
                $star['report_time'] = null;
                $star['report_num'] = 0;
                $star['report_user'] = '[]';
            } else {
                $open = strtotime($star['report_open']);
                $now  = time();
                $diff = $open - $now;

                $d = floor($diff / 3600 / 24);
                $h = floor(($diff % (3600 * 24)) / 3600);  //%取余
                $m = floor(($diff % (3600 * 24)) % 3600 / 60);
                $s = floor(($diff % (3600 * 24)) % 60);

                $content = '净化公屏中,预计';
                if ($d > 0) {
                    $content .= sprintf("%s天", $d);
                }
                if ($h > 0) {
                    $content .= sprintf("%s时", $h);
                }
                if ($m > 0) {
                    $content .= sprintf("%s分", $m);
                }
                if ($s > 0) {
                    $content .= sprintf("%s秒", $s);
                }
                $content .= '后解屏';
            }
        }

        if (false !== strpos($content, '净化公屏')) {
            $reportUser = json_decode($star['report_user'], true);
            $userLevel  = CfgUserLevel::getLevel($uid);
            $cleanCfg = Cfg::getCfg(Cfg::CLEAN_CHAT);
            if ($userLevel >= $cleanCfg['level']) {
                if (in_array($uid, $reportUser) == false) {
                    array_push($reportUser, $uid);
                    $reportNum = $cleanCfg['number'];
                    $reportTimestamp = $star['report_time'] ? strtotime($star['report_time']): time();
                    $forbiddenTime = date('Y-m-d H:i:s', bcadd($reportTimestamp, $cleanCfg['forbidden_time']));
                    Star::where('id', $userStar)->update([
                        'report_open' => Db::raw('if((`report_num`+1) >= '.$reportNum.', "' . $forbiddenTime . '", null)'),
                        'report_user' => json_encode($reportUser),
                        'report_num'  => Db::raw('`report_num`+1'),
                        'report_time' => Db::raw('if(`report_time` is null , "' . $currentDate . '", `report_time`)'),
                    ]);
                }
            }
        }

        Db::startTrans();
        try {
            // 保存聊天记录
            $res = self::create([
                'user_id' => $uid,
                'star_id' => $starid,
                'content' => $content,
                'create_time' => time(),
            ]);

            // 用户信息
            $res['user'] = User::with([
                'UserStar' => function ($query) {
                    $query->field('user_id,total_count,captain');
                },
                'UserExt' => function ($query) {
                    $query->field('user_id,badge_id');
                }
            ])->where('id', $uid)->field('id,nickname,avatarurl,type')->find();

            $res['user']['level'] = CfgUserLevel::getLevel($uid);
            $res['user']['headwear'] = HeadwearUser::getUse($uid);
            $res['user']['isLeader'] = FanclubUser::isLeader($uid);
            $res['user']['userBadge'] = BadgeUser::getUse($uid);

            if ($res['user']['type'] == 2) {
                Db::rollback();
                Common::res(['code' => 1, 'msg' => '你已被禁言']);
            }
            $openTime=UserStar::where('user_id',$uid)->value('open_time');
            if ($openTime && $openTime > time()){
                Db::rollback();
                Common::res(['code' => 1, 'msg' => '你已被禁言预计解封时间：' . date('Y-m-d H:i:s', $openTime)]);
            }
            RecTask::addRec($uid, 2);

            // RecChartAt::getAt($content, $res['id']);

            // 推送socket消息
            Gateway::sendToGroup('star_' . $starid, json_encode([
                'type' => 'chartMsg',
                'data' => $res
            ], JSON_UNESCAPED_UNICODE));

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
