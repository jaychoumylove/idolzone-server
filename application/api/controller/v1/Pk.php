<?php

namespace app\api\controller\v1;

use app\api\model\CfgPkTime;
use app\api\model\CfgUserLevel;
use app\api\model\HeadwearUser;
use app\api\model\PkStar;
use app\api\model\PkUser;
use app\api\model\Rec;
use app\api\model\User;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\BadgeUser;
use think\Db;

class Pk extends Base
{
    /**团战首页 */
    public function index()
    {
        $data = CfgPkTime::status();

        // 用户的报名状态
        $this->getUser();
        $data['myJoinType'] = PkUser::where('user_id', $this->uid)->where('pk_time', $data['whole_time'])->value('pk_type');

        Common::res(['data' => $data]);
    }

    /**参加团战 */
    public function join()
    {
        $this->getUser();

        $starid = $this->req('starid', 'integer');
        $pkType = $this->req('pk_type', 'integer');

        $pkStatus = CfgPkTime::status();
        if ($pkStatus['status'] !== 1) Common::res(['code' => 1, 'msg' => '现在不是报名时间段']);
        PkUser::joinCheck($this->uid, $starid, $pkType, $pkStatus['whole_time']);

        Db::startTrans();
        try {
            // 新增报名用户
            PkUser::newUser($this->uid, $starid, $pkType, $pkStatus['whole_time']);
            // 新增报名明星
            PkStar::newStar($starid, $pkType, $pkStatus['whole_time']);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res(['code' => 0, 'msg' => '参加成功']);
    }

    public function starrank()
    {
        $starid = $this->req('starid', 'integer');
        $pkType = $this->req('pk_type', 'integer');
        $pkId   = $this->req('pk_id', 'integer');
        $page   = $this->req('page', 'integer', 1);
        $size   = $this->req('size', 'integer', 10);

        // $pkStatus = CfgPkTime::status();
        $wholeTime = CfgPkTime::getRelativeTime($pkId);
        die($wholeTime);

        $res['list'] = PkStar::where('star_id', $starid)->where('pk_type', $pkType)->where('pk_time', $wholeTime)->page($page, $size)->select();

        // $res = [];
        // if ($pkId == $pkStatus['curPkTime']['id'] && $pkStatus['status'] == 1) {
        //     // 用户报名情况
        //     $res['list'] = PkUser::with('user')->where('star_id', $starid)->where('pk_type', $pkType)
        //         ->where('pk_time', $pkStatus['whole_time'])->order('id desc')->select();
        // } else { 
        //     // 明星排名

        // }

        Common::res(['data' => $res]);
    }


    // 团战信息排名
    public function pk()
    {
        $mid = input('mid');
        $type = input('type'); // 0 钻石场 1 鲜花场 
        $page = input('page', 1);
        $pkTime = input('pkTime', ''); // 带了表示查看历史记录
        $s = input('s', 0); // 查看用户列表
        $this->getUser();

        if (!$pkTime) {
            $data = $this->getPkStatus();
            $pkStatus = $data['status'];
            if (!isset($data['timeSpace'])) Common::res(['code' => 1, 'data' => $data]);

            $pkTime = date('Y-m-d', time()) . ' ' . $data['timeSpace']['start_time'] . ':00';
        } else {
            // 查看历史记录
            $yestoday = input('yestoday', 0);
            if ($yestoday) {
                $pkTime = date('Y-m-d H:i:s', strtotime($pkTime) - 3600 * 24 * $yestoday);
            }
            $pkStatus = 2;
        }

        if ($pkStatus == 2 && $s == 0) {
            // 明星排名
            $data['starList'] = Db::name('pk_star')->alias('pk')
                ->join('star m', 'm.id = pk.star_id')
                ->where(['pk.pk_time' => $pkTime, 'pk.pk_type' => $type])
                ->order('pk.hot desc,pk.id desc')->page($page, 10)
                ->field('pk.*,m.name,m.head_img_s as avatarurl')->select();
            $data['sAdm'] = Db::name('user')->where(['id' => $this->uid])->value('type');
        }

        if ($pkStatus == 1 || $s == 1) {
            if (!$mid) {
                $mid = Db::name('user_star')->where(['user_id' => $this->uid])->value('star_id');
            }
            // 用户排名
            $data['userList'] = Db::name('pk_user')->alias('pk')
                ->join('user u', 'u.id = pk.uid')

                ->where(['pk.pk_time' => $pkTime, 'pk.pk_type' => $type, 'pk.star_id' => $mid])
                ->order('pk.send_hot desc,pk.id desc')->page($page, 10)
                ->field('pk.*,u.avatarurl,u.nickname as name')->select();
            foreach ($data['userList'] as &$value) {
                $value['level'] = CfgUserLevel::getLevel($value['uid']);
                $value['headwear'] = HeadwearUser::getUse($value['uid']);
            }
            $data['joinNum'] = Db::name('pk_user')->where(['pk_time' => $pkTime, 'pk_type' => $type, 'star_id' => $mid])->count();
            $data['isAdm'] = Db::name('user_star')->where(['user_id' => $this->uid, 'star_id' => $mid])->value('captain');
            $data['uid'] = $this->uid;
        }

        Common::res([
            'code' => 0,
            'data' => $data
        ]);
    }

    // 团战状态
    public function getPkStatus()
    {
        // 最近时间段
        $pkTime = Db::name('cfg_pk_time')->select();

        foreach ($pkTime as $value) {
            $startTime = strtotime($value['start_time'] . ':00');
            $endTime = strtotime($value['end_time'] . ':00');
            if (time() >= $startTime && time() <= $endTime) {
                // 正在团战时间段
                $data['status'] = 2;
                // 时间点信息
                $data['timeSpace'] = $value;
                // 本场剩余时间
                $data['timeLeft'] = $endTime - time();

                break;
            } else {
                if (time() < $startTime) {
                    // 正在报名时间段
                    $data['status'] = 1;
                    $data['timeSpace'] = $value;
                    $data['timeLeft'] = $startTime - time();

                    break;
                } else {
                    $data['status'] = 0;
                }
            }
        }

        return $data;
    }

    // 团战状态sj// 团战首页
    public function getPkTime()
    {
        $this->getUser();

        // 最近时间段
        $pkTime = Db::name('cfg_pk_time')->select();
        $pk_type = input('type');
        $page = input('page', 1);
        $rankCurrent = input('rankCurrent', 0);
        $pkStatus = $this->getPkStatus();
        $data['curPkTime'] = $pkStatus['timeSpace'];

        $week = 0;
        // 粉丝排名
        if ($rankCurrent == 0) {
            // 上一场用户贡献排名
            if ($pkStatus['status'] == 1) {
                // 正在报名 上一场数据
                $lastPkTime = Db::name('pk_settle')->where('is_settle', 1)->order('id desc')->value('pk_time');
            } elseif ($pkStatus['status'] == 2) {
                // 团战开始 当前场数据
                $lastPkTime = date('Y-m-d', time()) . ' ' . $pkStatus['timeSpace']['start_time'] . ':00';
            }
            $data['userList'] = Db::name('pk_user_rank')->alias('pk')
                ->join('user u', 'u.id = pk.uid')
                ->where('pk.last_pk_time', $lastPkTime)
                ->order('pk.last_pk_count desc')->page($page, 10)
                ->field('pk.*,u.avatarurl,u.nickname as name')->select();

            // 分享信息
            $myRankInfo = Db::name('pk_user_rank')->where('uid', $this->uid)->field('m_name,last_pk_time,last_pk_count')->find();
            if ($myRankInfo['last_pk_time'] == $lastPkTime) {
                $data['shareContent'] = '我在团战中为' . $myRankInfo['m_name'] . '贡献了' . $myRankInfo['last_pk_count'] . '人气，快来为我点赞吧~';
            } else {
                $data['shareContent'] = '';
            }
        } else {
            // 总共用户贡献排名
            $data['userList'] = Db::name('pk_user_rank')->alias('pk')
                ->join('user u', 'u.id = pk.uid')
                ->where('pk.week', $week)
                ->order('pk.total_count desc')->page($page, 10)
                ->field('pk.*,u.avatarurl,u.nickname as name')->select();
        }

        foreach ($data['userList'] as &$value) {
            $value['headwear'] = HeadwearUser::getUse($value['uid']);
        }

        foreach ($pkTime as $key => &$value) {
            $startTime = strtotime($value['start_time'] . ':00');
            $endTime = strtotime($value['end_time'] . ':00');

            $value['pkTime'] = date('Y-m-d', time()) . ' ' . $value['start_time'] . ':00';

            if (time() < $startTime) {
                // 还没开始
                $value['status'] = 1;
                // 距开场剩余时间
                $value['timeLeft'] = $startTime - time();
                // 可以参加
                $value['canJoin'] = $pkStatus['timeSpace']['id'] == $value['id'];
                // 
                $value['isJoin'] = Db::name('pk_user')->where(['pk_time' => $value['pkTime'], 'uid' => $this->uid])->value('pk_type');
            } else if (time() >= $startTime && time() <= $endTime) {
                // 正在团战时间段
                $value['status'] = 2;
                // 本场剩余时间
                $value['timeLeft'] = $endTime - time();
            } else {
                // 已结束
                $value['status'] = 0;
                $value['timeLeft'] = 0;
            }
        }
        $data['status'] = $pkStatus['status'];
        $data['pkTime'] = $pkTime;
        $data['arcId'] = 8245;

        // foreach ($data['userList'] as $key => &$value) {
        //     $value['headwear'] = HeadwearUser::getUse($value['uid']);
        // }
        Common::res(['data' => $data]);
    }

    /**发放奖励 */
    public function autoSettle()
    {
        $data = $this->getPkStatus();

        $pkTime = date('Y-m-d', time()) . ' ' . $data['timeSpace']['start_time'] . ':00';
        $isExist = Db::name('pk_settle')->where(['pk_time' => $pkTime])->find();

        if (!$isExist) {
            Db::startTrans();
            try {
                Db::name('pk_settle')->insert([
                    'pk_time' => $pkTime,
                    'is_settle' => 0
                ]);

                $week = 0;
                // $week = date('oW', time());
                // 发放上一场次奖励
                $noSettleTimeList = Db::name('pk_settle')->where(['is_settle' => 0])->where('pk_time', '<>', $pkTime)->column('pk_time');
                foreach ($noSettleTimeList as $pk_time) {
                    for ($i = 0; $i < 2; $i++) {
                        $pk_type = $i;

                        $star_ids = Db::name('pk_star')->where(['pk_time' => $pk_time, 'pk_type' => $pk_type])->order('hot desc,id desc')->column('star_id');

                        foreach ($star_ids as $rank => $star_id) {
                            // 奖励数额
                            $awards = Db::name('cfg_pk_awards')->where(['rank' => ['<=', $rank + 1], 'type' => $pk_type])->order('rank desc')->find();
                            $uids = Db::name('pk_user')->where(['pk_time' => $pk_time, 'pk_type' => $pk_type, 'star_id' => $star_id])->order('send_hot desc,id desc')->column('uid');

                            if ($uids) {
                                // 发奖牌
                                if ($rank + 1 == 1) {
                                    $paizi = 'gold';
                                } else if ($rank + 1 == 2) {
                                    $paizi = 'silver';
                                } else if ($rank + 1 == 3) {
                                    $paizi = 'bronze';
                                } else {
                                    $paizi = '';
                                }

                                if ($paizi) {
                                    // 给123名加牌子
                                    Db::name('pk_user_rank')->where(['uid' => $uids[0], 'mid' => $star_id, 'week' => $week])->update([
                                        $paizi => Db::raw($paizi . '+1'),
                                        'last_pk_medal' => $paizi
                                    ]);

                                    //团战徽章
                                    if ($paizi == 'gold') {
                                        $total_paizi = Db::name('pk_user_rank')->where(['uid' => $uids[0], 'mid' => $star_id, 'week' => $week])->value('gold');
                                        BadgeUser::addRec($uids[0], 6, $total_paizi); //stype=6团战徽章
                                    }
                                }

                                Db::name('pk_settle_i')->insert([
                                    'awards' => json_encode($awards),
                                    'uids' => json_encode($uids),
                                    'pk_type' => $pk_type,
                                ]);
                            }
                        }
                    }

                    Db::name('pk_settle')->where(['pk_time' => $pk_time])->update(['is_settle' => 1]);
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function pkUserRank()
    {
        $page = input('page', 1);
        $week = date('oW', time());

        $data['userList'] = Db::name('pk_user_rank')->alias('pk')
            ->join('user u', 'u.id = pk.uid')
            ->where('week', $week)
            ->order('pk.last_pk_count desc')->page($page, 10)
            ->field('pk.*,u.head_img,u.nickname as name')->select();

        Common::res($data);
    }

    public function pkDianzan()
    {
        $this->getUser();
        $uid =  input('uid');

        $userZan = Db::name('pk_zan')->where('uid', $this->uid)->find();
        if (!$userZan) {
            try {
                Db::name('pk_zan')->insert([
                    'uid' => $this->uid
                ]);
            } catch (\Exception $e) {
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
            $userZan['zan'] = 0;
        } else if ($userZan['zan'] && date('Ymd', $userZan['time']) != date('Ymd')) {
            Db::name('pk_zan')->where('uid', $this->uid)->update([
                'zan' => 0
            ]);
            $userZan['zan'] = 0;
        }

        if ($userZan['zan'] >= 8) {
            Common::res(['code' => 4, 'msg' => '今日点赞次数已用完']);
        } else {
            Db::name('pk_zan')->where('uid', $this->uid)->update([
                'zan' => Db::raw('zan+1'),
                'time' => time()
            ]);

            Db::name('pk_user_rank')->where('uid', $uid)->update([
                'total_zan' => Db::raw('total_zan+1'),
            ]);
        }
        Common::res(['code' => 0, 'msg' => '点赞成功']);
    }

    /**团战报名 */
    public function pkJoin()
    {
        $star_id = input('starid');
        $type = input('type');
        $this->getUser();

        // $redis = new Redis;
        // $lockName = "pk_{$star_id}_{$type}";
        // if ($redis->connectSuccess) {
        //     $getLock = $redis->lock($lockName, 1);
        //     if (!$getLock) Common::res(['code' => 1, 'msg' => '当前报名人数过多，请稍后再试']);
        // }

        // 是否被禁赛
        $openTime = Db::name('pk_user_ban')->where('uid', $this->uid)->value('open_time');
        if ($openTime && $openTime > time()) Common::res(['code' => 1, 'msg' => '预计解封时间：' . date('Y-m-d H:m:s', $openTime)]);

        $data = $this->getPkStatus();
        if ($data['status'] !== 1) Common::res(['code' => 1, 'msg' => '现在不是报名时间']);
        // 几点场时间
        $pkTime = date('Y-m-d', time()) . ' ' . $data['timeSpace']['start_time'] . ':00';
        // 报名用户
        $userExist  = Db::name('pk_user')->where(['pk_time' => $pkTime, 'uid' => $this->uid])->value('id');
        if ($userExist) Common::res(['code' => 1, 'msg' => '你已报名其他场次']);
        $count      = Db::name('pk_user')->where(['pk_time' => $pkTime, 'pk_type' => $type, 'star_id' => $star_id])->count();
        if ($count >= 100) Common::res(['code' => 1, 'msg' => '报名人数已满']);

        $userTotalCount = UserStar::where('user_id', $this->uid)->where('star_id', $star_id)->value('total_count');
        $userLevel = CfgUserLevel::where('total', '<=', $userTotalCount)->max('level');
        $minLevel = Db::name('pk_minlevel')->where(['star_id' => $star_id])->value('min_level');
        if ($userLevel < $minLevel) Common::res(['code' => 1, 'msg' => '粉丝等级过低，请提升等级后再来']);

        Db::startTrans();
        try {
            // 加入团战
            $pre = config('database.prefix');
            $isDone = Db::execute("INSERT INTO {$pre}pk_user (uid, star_id, pk_time, pk_type) 
            SELECT {$this->uid}, {$star_id}, '{$pkTime}', {$type} 
            FROM DUAL WHERE EXISTS
            (SELECT count(*) as c FROM {$pre}pk_user where star_id = {$star_id} and pk_time = '{$pkTime}' and pk_type = {$type} having c < 100);");
            if (!$isDone) Common::res(['code' => 1, 'msg' => '报名人数已满']);

            if ($type == 0) $pkTitle = '钻石场';
            else $pkTitle = '鲜花场';

            // 日志
            Rec::addRec([
                'user_id' => $this->uid,
                'content' => '加入团战，' . substr($pkTime, 11) . $pkTitle,
            ]);

            // 新增报名明星
            $starExist = Db::name('pk_star')->where(['pk_time' => $pkTime, 'pk_type' => $type, 'star_id' => $star_id])->value('id');
            if (!$starExist) {
                Db::name('pk_star')->insert([
                    'star_id' => $star_id,
                    'pk_time' => $pkTime,
                    'pk_type' => $type,
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 1, 'msg' => '报名人数过多，请稍后再试', 'data' => $e->getMessage()]);
        }

        // if ($redis->connectSuccess) $redis->unlock($lockName);

        Common::res(['code' => 0, 'msg' => '参加成功']);
    }

    /**踢人 */
    public function pkOut()
    {
        $mid = input('mid');
        $uid = input('uid');
        $time = input('time', 0); // 禁赛时长
        $this->getUser();

        if ($this->uid != $uid && UserStar::where('user_id', $this->uid)->where('star_id', $mid)->value('captain') == 0) {
            Common::res(['code' => 1, 'msg' => '抱歉，你没有权限']);
        }

        if ($time) {
            // 封禁
            $isDone = Db::name('pk_user_ban')->where('uid', $uid)->update(['open_time' => time() + $time * 3600]);
            if (!$isDone) {
                $isDone = Db::name('pk_user_ban')->insert([
                    'uid' => $uid,
                    'open_time' => time() + $time * 3600
                ]);
            }
            Common::res(['msg' => '封禁成功']);
        } else {
            // 踢人
            $data = $this->getPkStatus();
            $pkTime = date('Y-m-d', time()) . ' ' . $data['timeSpace']['start_time'] . ':00';

            $res = Db::name('pk_user')->where(['uid' => $uid, 'star_id' => $mid, 'pk_time' => $pkTime])->delete();

            if ($res) {
                // 记录
                if ($this->uid != $uid) {
                    Rec::addRec([
                        'user_id' => $this->uid,
                        'content' => '你将' . User::where('id', $uid)->value('nickname') . '移出了PK团战'
                    ]);
                    Rec::addRec([
                        'user_id' => $uid,
                        'content' => '你被' . User::where('id', $this->uid)->value('nickname') . '移出了PK团战'
                    ]);
                } else {
                    Rec::addRec([
                        'user_id' => $uid,
                        'content' => '你退出了PK团战'
                    ]);
                }

                Common::res(['msg' => '移出成功']);
            } else {
                Common::res(['code' => 1, 'msg' => '移出失败']);
            }
        }
    }

    /**团战订阅 */
    public function pkSubscribe()
    {
        $res = Db::name('pk_user')->where('uid', $this->uid)->order('id desc')->limit(1)->update(['is_subscribe' => 1]);
        Common::res(['status' => 1, 'msg' => '订阅成功']);
    }

    /**团战推送 需要定时任务 */
    public function pkPush()
    {
        $data = $this->getPkStatus();
        $pkTime = date('Y-m-d') . ' ' . $data['timeSpace']['start_time'] . ':00';

        // 当前场次，已订阅消息的用户openid和formid
        $query = Db::query("select form_id as formid,openid from 
            (select * from api_formid where openid in 
            (SELECT u.openid FROM 
            `api_pk_user` p join api_user u on u.id = p.uid
            where pk_time = '${pkTime}' and is_subscribe = 1)
            ORDER BY create_time desc) a
            GROUP BY openid");

        $template_id = "T54MtDdRAPe8kNNtt2tQlj7P7ut7yEe-F8-CaMrKcvw";

        foreach ($query as $value) {
            $pushData = [
                "touser" => $value['openid'],
                "template_id" => $template_id,
                "page" => "/pages/index/index",
                "form_id" => $value['formid'],
                "data" => [
                    "keyword1" => [
                        "value" => "团战已开始"
                    ],
                    "keyword2" => [
                        "value" => "爱豆圈子"
                    ]
                ],
                "emphasis_keyword" => "keyword1.DATA"
            ];

            // (new Weapp())->sendTemplateMessage($pushData);
        }
    }
}
