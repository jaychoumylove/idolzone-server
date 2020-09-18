<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\model\Father as ModelFather;
use think\Db;
use app\base\service\Common;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

class UserStar extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public function achievement()
    {
        return $this->hasOne ('UserAchievementHeal', 'user_id', 'user_id');
    }

    /**获取用户爱豆id */
    public static function getStarId($uid)
    {
        return self::where('user_id', $uid)->order('id desc')->value('star_id');
    }

    /**用户贡献排名list */
    public static function getRank($starid, $field, $page, $size, $open_id = 0)
    {
        if ($field == 'thisbirth_rank') {
            // 生日应援
            $list = StarBirthRank::getRank($starid, $page, $size);
        } else if ($open_id) {
            // 开屏图，
            $list = OpenRank::with('User')->where('open_id', $open_id)->where('count', '<>', 0)->order('count desc,id asc')->page($page, $size)->select();
        } else {
            $where = $starid ? ['star_id' => $starid] : '1=1';
            if ($starid) $list = self::with('User')->where($where)->where([$field => ['neq', 0]])->order($field . ' desc')->field("*,{$field} as hot")->page($page, $size)->select();
            else $list = self::with('User')->with('Star')->where($where)->where([$field => ['neq', 0]])->order($field . ' desc')->field("*,{$field} as hot")->page($page, $size)->select();
        }

        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
        foreach ($list as &$value) {
            $value['user']['level'] = CfgUserLevel::getLevel($value['user']['id']);
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
        }
        return $list;
    }

    /**
     * userStar数据变动
     * @param int $uid
     * @param int $starid
     * @param array 修改的内容
     */
    public static function change($uid, $starid, $changArr)
    {
        $update = [];
        foreach ($changArr as $key => $value) {
            if ($value > 0) {
                // 增加
                $value = '+' . $value;
            } else if ($value < 0) {
                //减少
                
            } else {
                continue;
            }
            $update[$key] = Db::raw($key . $value);
            if ($key == 'pick_days')  $update['pick_time'] = time();
        }
        if (!$update) return;
        self::where(['user_id' => $uid, 'star_id' => $starid])->update($update);
    }


    /**
     * userStar数据变动
     * @param int $uid
     * @param set $act ['pick','like','mass']
     * @param int $starid
     * @param int $count 改变的数值
     * @param set $type 1金豆，2鲜花，3旧豆子
     */
    public static function changeHandle($uid, $act, $starid = 0, $count = 1, $type = 0)
    {
        $primary_where = $starid ? ['user_id' => $uid, 'star_id' => $starid] : ['user_id' => $uid];
        $primary = self::get($primary_where);
        $starid = $starid ? $starid : $primary['star_id'];

        $changArr = [];

        //打榜
        if ($act == 'pick') {
            $changArr = [
                'total_count' => $count,
                'thisday_count' => $count,
                'thisweek_count' => $count,
                'thismonth_count' => $count,
                'achievement_week_count' => $count,
                'achievement_month_count' => $count,
            ];
            //送鲜花
            if ($type == 2) {
                $changArr = array_merge ($changArr, [
                    'total_flower' => $count,
                    'day_flower' => $count,
                    'achievement_flower' => $count,
                ]);

                BadgeUser::addRec($uid, 2, $primary['total_flower'] + $count); //stype=2鲜花徽章
            }

            //打榜天数
            $today_picked = date('Ymd') == date('Ymd', $primary['pick_time']);
            if (!$today_picked) {
                $changArr += ['pick_days' => 1];
                BadgeUser::addRec($uid, 1, $primary['pick_days'] + 1); //stype=1打榜次数徽章
            }

            //2020年开始，且爱豆今天生日,获得生日徽章
            if (date('Y') >= 2019 && in_array($starid, Star::where('birthday', (int) date('md'))->column('id'))) {
                BadgeUser::addRec($uid, 8, 1); //stype=8生日徽章
            }
        }

        //点赞
        elseif ($act == 'like') {
            $changArr += ['like_count' => $count];
            BadgeUser::addRec($uid, 5, $primary['like_count'] + $count); //stype=5集赞徽章
        }

        //集结
        elseif ($act == 'mass') {
            $changArr += ['fanclub_mass_times' => $count];
            BadgeUser::addRec($uid, 3, $primary['fanclub_mass_times'] + $count); //stype=3集结徽章
        }
        self::change($uid, $starid, $changArr);
    }


    /**加入爱豆圈子 */
    public static function joinNew($starid, $uid,$platform)
    {
        Db::startTrans();
        try {
            $userType = User::where('id', $uid)->value('type');
            if ($userType == 1) {
                // 管理员
                $uid = self::getVirtualUser($starid, $uid,$platform);
            }

            if (!self::get(['user_id' => $uid, 'star_id' => $starid])) {
                // Common::res(['code' => 302]);
                self::create([
                    'user_id' => $uid, 'star_id' => $starid
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
        return $uid;
    }

    /**
     * 获取该管理员在该圈子的虚拟用户
     * @param mixed $uid 管理员uid
     * @return mixed 虚拟用户uid
     */
    public static function getVirtualUser($starid, $uid,$platform)
    {
        $user = User::where('id', $uid)->find();
        if (strpos($user['openid'], '@') !== false) {
            Common::res(['code' => 1, 'msg' => '请尝试清除缓存后再试']);
        }
        $oldStarid = UserStar::where('user_id', $uid)->value('star_id');
        if (!$oldStarid) $oldStarid = 0;
        // 旧 带上oldStarid后缀
        User::where('id', $uid)->update([
            'openid' => $user['openid'] . '@' . $oldStarid,
            'unionid' => $user['unionid'] . '@' . $oldStarid,
            'phoneNumber' => $user['phoneNumber'] ? $user['phoneNumber'] . '@' . $oldStarid : $user['openid'] . '@' . $oldStarid
        ]);

        // 新的角色
        $virtualUid = User::where(['openid' => $user['openid'] . '@' . $starid])->value('id');
        if (!$virtualUid) {
            $virtualUid = User::createVirtualUser([
                'platform' => $platform,
                'openid' => $user['openid'],
                'unionid' => $user['unionid'],
            ]);
        } else {
            User::where(['openid' => $user['openid'] . '@' . $starid])->update([
                'openid' => $user['openid'],
                'unionid' => $user['unionid'],
                'phoneNumber' => $user['phoneNumber']
            ]);
        }

        return $virtualUid;
    }

    /**退出圈子 */
    public static function exit($uid)
    {
        $ext = UserExt::get(['user_id' => $uid]);
        if ((time() - $ext['exit_group_time']) > 3600 * 24 * 90) {
            // 90才能退一次
            self::destroyConform($uid); // 退圈
            
        } else {
            Common::res(['code' => 1, 'msg' => '退出圈子失败，上次退出圈子时间为' . date('Y-m-d', $ext['exit_group_time'])]);
        }
    }

    /**退出相关操作 */
    public static function destroyConform($uid)
    {
        $user = self::where(['user_id' => $uid])->find();
        $user = json_decode(json_encode($user), true);
        unset($user['id']);

        Db::startTrans();
        try {
            // 记录退圈时间
            UserExt::where(['user_id' => $uid])->update(['exit_group_time' => time()]);
        
            //删除本条记录
            self::where(['user_id' => $uid])->delete();
            Db::name('user_star_bakup')->insert($user);
    
            //删除圈子相关徽章
            BadgeUser::where(['uid' => $uid])->where('stype', 'in', [1, 2, 3, 5, 6, 8])->delete();
    
            //删除pk相关
            Db::name('pk_user_rank')->where(['uid' => $uid])->delete();
            Db::name('pk_zan')->where(['uid' => $uid])->delete();
            Db::name('pk_user')->where(['uid' => $uid])->delete();
    
            //删除粉丝团相关,用团长的身份踢出，才不扣钻石
            $fanclub_id = FanclubUser::where('user_id',$uid)->value('fanclub_id');
            if($fanclub_id) $fanLeader = Fanclub::where('id',$fanclub_id)->value('user_id');
            if(isset($fanLeader) && $fanLeader) Fanclub::exitFanclub($fanLeader,$uid);

            //退出家族
            Family::exitFamily($uid,$uid);
    
            //退出师徒
            if(ModelFather::where('father_uid',$uid)->count()) Common::res(['code' => 1, 'msg' => '你还有徒弟未解除，不能退圈']);
            ModelFather::exit($uid);

            UserManor::where('user_id', $uid)->update(['star_id' => 0]);
            $animalId = CfgAnimal::where('type', CfgAnimal::SECRET)
                ->where('star_id', '>', 0)
                ->column('id');

            UserAnimal::where('user_id', $uid)
                ->where('animal_id', 'in', $animalId)
                ->delete();
    
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $starid, $field)
    {
        if ($field == 'thisbirth_rank') {
            $res = StarBirthRank::where('user_id', $uid)->where('star_id', $starid)->find();
            $res['score'] = $res['count'];
        } else {
            $where = $starid ? ['user_id' => $uid, 'star_id' => $starid] : ['user_id' => $uid];
            $res = self::where($where)->field($field . ',like_count')->find();
            $res['score'] = $res[$field];
        }

        if ($res['score']) {
            if ($field == 'thisbirth_rank') {
                $res['rank'] = StarBirthRank::where('star_id', $starid)->where('count', '>', $res['score'])->count() + 1;
            } else {
                $where = $starid ? ['star_id' => $starid] : '1=1';
                $res['rank'] = self::where($where)->where($field, '>', $res['score'])->count() + 1;
            }
        } else {
            $res['rank'] = 'no';
        }

        // 等级
        $res['level'] = CfgUserLevel::getLevel($uid);
        // 头饰
        $res['headwear'] = HeadwearUser::getUse($uid);

        return $res;
    }

    /**
     * 活动信息
     * @param int $type 0：阶段解锁 1：7日解锁
     */
    public static function getActiveInfo($uid, $starid, $active_id)
    {
        // 活动信息
        $res = CfgActive::get($active_id);
        if(!$res) Common::res(['code' => 1, 'msg' => '参数错误']);

        $tmp = CfgActiveReplace::where('id',$active_id)->where('ex_star_id',$starid)->find();
        if($tmp) $res = $tmp;

        // 离活动结束还剩
        $res['active_end'] = strtotime(json_decode($res['active_date'], true)[1]) - time();
        // 活动说明
        $res['notice'] = json_decode($res['notice'], true);
        // 完成进度
        $res['progress'] = RecActive::getProgress($starid, $res['id'], $res['min_days']);
        // 我的打卡信息
        $res['self'] = RecActive::getOneInfo($uid, $starid, $active_id);

        // 分享text
        $res['shareText'] = '为STARNAME打卡' . $res['min_days'] . '天，解锁' . $res['title'];

        // canvas活动标题
        // $res['canvas_title'] = Cfg::getCfg('canvas_title_active');

        return $res;
    }

    /**活动 打卡 */
    public static function setCard($uid, $starid, $active_id)
    {
        CfgActive::checkInDate($active_id);

        // 我的打卡信息
        $myCardInfo = RecActive::getOneInfo($uid, $starid, $active_id);
        if ($myCardInfo['is_card_today']) Common::res(['code' => 1, 'msg' => '你今天已经打卡了哦']);
        if ($myCardInfo['card_need_userlevel']) Common::res(['code' => 1, 'msg' => "打卡资源有限\n用户等级需要达到".$myCardInfo['card_need_userlevel']."级\n请先为爱豆冲榜提升等级"]);

        // 打卡数+1
        RecActive::addClock($uid, $starid, $active_id);
    }

    /**推送解锁 */
    public static function push($starid, $count)
    {
        $activeInfo = Cfg::getCfg('active_info');
        // 当前打卡数
        $beforeCards = self::where('star_id', $starid)->sum('active_card_days');
        // 之后打卡数
        $afterCards = $beforeCards + $count;
        $beforeFee = 0;
        $afterFee = 0;
        foreach ($activeInfo as $value) {
            if ($beforeCards < $value['count']) {
                break;
            } else {
                $beforeFee = $value['fee'];
            }
        }
        foreach ($activeInfo as $value) {
            if ($afterCards < $value['count']) {
                break;
            } else {
                $afterFee = $value['fee'];
            }
        }
        if ($beforeFee != $afterFee) {
            // 确认推送
            Common::requestAsync('https://' . $_SERVER['HTTP_HOST'] . '/api/v1/auto/sendTmp', http_build_query([
                'starid' => $starid,
                'fee' => $afterFee
            ]));
        }
    }

    /**
     * 永不退圈
     * @param $uid
     * @return bool
     */
    public static function neverQuit($uid)
    {
        $updated = UserExt::where(['user_id' => $uid])->update(['exit_group_time' => 2147483647]);
        return (bool)$updated;
    }

    /**
     * 每日鲜花贡献前10名获得花神成就挂饰
     *
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function settleFlower()
    {
        $topTenFlower = self::where('day_flower', '>', 0)
            ->order ([
                'day_flower' => 'desc',
                'update_time' => 'asc',
                'id' => 'asc'
            ])
            ->limit (10)
            ->select ();
        if (is_object ($topTenFlower)) $topTenFlower = $topTenFlower->toArray ();

        foreach ($topTenFlower as $item) {
            UserAchievementHeal::recordTime ($item['user_id'], $item['star_id'], UserAchievementHeal::TIMER, UserAchievementHeal::FLOWER);
        }

        $ids = array_column($topTenFlower, 'user_id');

        (new RecPanaceaTask())->settleRank($ids, CfgPanaceaTask::FLOWER_RANK);
    }

    /**
     * 当日注册用户 每日贡献前10名获得明日之星成就挂饰
     *
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function settleCount()
    {
        $format = "Y-m-d H:i:s";
        $start = date($format, strtotime('yesterday'));
        $end = date($format, time ());

        $newGuys = User::where('create_time', '>', $start)
            ->where('create_time', '<', $end)
            ->select ();
        if (is_object ($newGuys)) $newGuys = $newGuys->toArray ();

        $newGuysId = array_column ($newGuys, 'id');

        $topTen = self::where('thisday_count', '>', 0)
            ->where('user_id', 'in', $newGuysId)
            ->order ([
                'thisday_count' => 'desc',
                'update_time' => 'asc',
                'id' => 'asc'
            ])
            ->limit (10)
            ->select ();
        if (is_object ($topTen)) $topTen = $topTen->toArray ();

        foreach ($topTen as $item) {
            UserAchievementHeal::recordTime ($item['user_id'], $item['star_id'], UserAchievementHeal::TIMER, UserAchievementHeal::NEW_GUY);
        }
    }
}
