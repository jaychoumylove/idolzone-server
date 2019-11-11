<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;
use app\base\service\WxAPI;

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
            $list = self::with('User')->where('star_id', $starid)->where([$field => ['neq', 0]])->order($field . ' desc')->field("*,{$field} as hot")->page($page, $size)->select();
        }

        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
        foreach ($list as &$value) {
            $value['user']['level'] = CfgUserLevel::getLevel($value['user']['id']);
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
        }
        return $list;
    }

    /**贡献度改变 */
    public static function change($uid, $starid, $hot)
    {
        self::where(['user_id' => $uid, 'star_id' => $starid])->update([
            'total_count' => Db::raw('total_count+' . $hot),
            'thisday_count' => Db::raw('thisday_count+' . $hot),
            'thisweek_count' => Db::raw('thisweek_count+' . $hot),
            'thismonth_count' => Db::raw('thismonth_count+' . $hot),
        ]);
    }

    /**加入爱豆圈子 */
    public static function joinNew($starid, $uid)
    {
        Db::startTrans();
        try {
            $userType = User::where('id', $uid)->value('type');
            if ($userType == 1) {
                // 管理员
                $uid = self::getVirtualUser($starid, $uid);
            }

            if (!self::get(['user_id' => $uid, 'star_id' => $starid])) {
                self::create([
                    'user_id' => $uid, 'star_id' => $starid
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
        return $uid;
    }

    /**
     * 获取该管理员在该圈子的虚拟用户
     * @param mixed $uid 管理员uid
     * @return mixed 虚拟用户uid
     */
    public static function getVirtualUser($starid, $uid)
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
            'unionid' => $user['unionid'] . '@' . $oldStarid
        ]);

        // 新的角色
        $virtualUid = User::where(['openid' => $user['openid'] . '@' . $starid])->value('id');
        if (!$virtualUid) {
            $virtualUid = User::createVirtualUser([
                'openid' => $user['openid'],
                'unionid' => $user['unionid'],
            ]);
        } else {
            User::where(['openid' => $user['openid'] . '@' . $starid])->update([
                'openid' => $user['openid'],
                'unionid' => $user['unionid']
            ]);
        }

        return $virtualUid;
    }

    /**退出圈子 */
    public static function exit($uid)
    {
        $ext = UserExt::get(['user_id' => $uid]);
        if (time() - $ext['exit_group_time'] > 3600 * 24 * 365 / 2) {
            // 半年才能退一次
            Db::startTrans();
            try {
                // 退圈
                self::destroy(['user_id' => $uid]);
                // 记录退圈时间
                UserExt::where(['user_id' => $uid])->update(['exit_group_time' => time()]);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        } else {
            Common::res(['code' => 1, 'msg' => '退出圈子失败，上次退出圈子时间为' . date('Y-m-d', $ext['exit_group_time'])]);
        }
    }

    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $starid, $field)
    {
        if ($field == 'thisbirth_rank') {
            $res = StarBirthRank::where('user_id', $uid)->where('star_id', $starid)->find();
            $res['score'] = $res['count'];
        } else {
            $res = self::where(['user_id' => $uid, 'star_id' => $starid])->field($field . ',like_count')->find();
            $res['score'] = $res[$field];
        }

        if ($res['score']) {
            if ($field == 'thisbirth_rank') {
                $res['rank'] = StarBirthRank::where('star_id', $starid)->where('count', '>', $res['score'])->count() + 1;
            } else {
                $res['rank'] = self::where('star_id', $starid)->where($field, '>', $res['score'])->count() + 1;
            }
        } else {
            $res['rank'] = '未上榜';
        }

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
        // 离活动结束还剩
        $res['active_end'] = strtotime(json_decode($res['active_date'], true)[1]) - time();
        // 活动说明
        $res['notice'] = json_decode($res['notice'], true);
        // 完成进度
        $res['progress'] = RecActive::getProgress($starid, $res['id'], $res['min_days']);
        // 我的打卡信息
        $res['self'] = RecActive::getOneInfo($uid, $starid, $active_id);

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
}
