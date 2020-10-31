<?php


namespace app\api\model;


use app\api\controller\v1\Pk;
use app\base\model\Base;
use app\base\service\Common;
use PDOStatement;
use think\Collection;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Model;
use Throwable;

class UserAchievementHeal extends Base
{
    const TIMER = 259200; // 60*60*24*3

    const FLOWER_TIME = 'flower_time';
    const NEW_GUY     = 'newguy';
    const FLOWER      = 'flower';
    const PK          = 'pk';

    public static $typeMap = [
        self::FLOWER_TIME => CfgHeadwear::FLOWER_TIME,
        self::NEW_GUY     => CfgHeadwear::NEW_GUY,
        self::FLOWER      => CfgHeadwear::FLOWER,
        self::PK          => CfgHeadwear::PK
    ];

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function userStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'user_id');
    }

    public function star()
    {
        return $this->hasOne('Star', 'id', 'star_id');
    }

    public static function cleanDayInvite()
    {
        self::where('type', self::FLOWER_TIME)
            ->where('invite_day', '>', 0)
            ->update(['invite_day' => 0]);
    }

    /**
     * @param $user_id
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws Exception
     */
    public static function addInvite($user_id)
    {
        $type        = self::FLOWER_TIME;
        $map         = compact('type', 'user_id');
        $exist       = (new self())->readMaster()->where($map)->find();
        $currentTime = time();
        if (empty($exist)) {
            $data = [
                'invite_day'   => 1,
                'invite_sum'   => 1,
                'invite_count' => 1,
                'invite_time'  => $currentTime,
                'sum_time'     => 0,
                'count_time'   => 0,
                'star_id'      => UserStar::getStarId($user_id)
            ];

            self::create(array_merge($data, $map));
        } else {
            $isDay     = date('Y-m-d', $currentTime) == date('Y-m-d', $exist['invite_time']);
            $inviteDay = $isDay ? $exist['invite_day'] : 0;
            $data      = [
                'invite_day'   => bcadd($inviteDay, 1),
                'invite_sum'   => bcadd($exist['invite_sum'], 1),
                'invite_count' => bcadd($exist['invite_count'], 1),
                'invite_time'  => $currentTime,
            ];

            $isSettle = $data['invite_count'] >= 100;
            if ($isSettle) {
                $data['invite_count'] = bcsub($data['invite_count'], 100);
            }

            self::where('id', $exist['id'])->update($data);

            if ($isSettle) {
                $starId = UserStar::getStarId($user_id);
                self::recordTime($user_id, $starId, self::TIMER, self::FLOWER_TIME);
            }
        }
    }

    public static function getMyRank($typeField, $rankTypeField, $user_id, $extra)
    {
        switch ($typeField) {
            case self::FLOWER_TIME:
                $info = self::getMyFlowerTimeRank($rankTypeField, $user_id, $extra);
                break;
            case self::FLOWER:
                $info = self::getMyFlowerRankList($rankTypeField, $user_id, $extra);
                break;
            case self::PK:
                $info = self::getMyPkRankList($rankTypeField, $user_id, $extra);
                break;
            case self::NEW_GUY:
                $info = self::getMyNewGuyRankList($rankTypeField, $user_id, $extra);
                break;
            default:
                break;
        }

        return empty($info) ? null : $info;
    }

    private static function getMyFlowerTimeRank($rankTypeField, $user_id, $extra)
    {
        $info = self::with(['user', 'star'])->where('user_id', $user_id)->find();
        if (empty($info)) return null;

        $headWear = CfgHeadwear::where('key', CfgHeadwear::FLOWER_TIME)->find();
        $field    = $rankTypeField == 'today' ? 'invite_day' : 'invite_sum';
        $starMap  = [];
        if ($rankTypeField == 'star') {
            $starMap['star_id'] = $extra['star_id'];
        }
        $rank = 'no';
        if ($info[$field] > 0) {
            $rank = self::where($field, '>', $info[$field])->where($starMap)->count();
            $rank = (int)bcadd($rank, 1);
        }
        $info['rank']     = $rank;
        $info['count']    = $info[$field];
        $info['num']      = (int)bcdiv($info['sum_time'], self::TIMER);
        $info['img']      = $headWear['img'];
        $info['headwear'] = HeadwearUser::getUse($info['user_id']);

        return $info;
    }

    private static function getMyFlowerRankList($rankTypeField, $user_id, $extra)
    {
        $info = UserStar::with(['User', 'Star'])->where('user_id', $user_id)->find();
        if (empty($info)) return [];
        $headWear = CfgHeadwear::where('key', CfgHeadwear::FLOWER)->find();

        $fieldMap = [
            'today'     => 'day_flower',
            'yesterday' => 'yesterday_flower',
            'star'      => 'achievement_flower',
            'all'       => 'achievement_flower',
        ];
        $field    = $fieldMap[$rankTypeField];

        $starMap = [];
        if ($rankTypeField == 'star') {
            $starMap['star_id'] = $extra['star_id'];
        }

        $sumTime = self::where('user_id', $user_id)
            ->where('type', self::FLOWER)
            ->value('sum_time', 0);

        $rank = 'no';
        if ($info[$field] > 0) {
            $rank = UserStar::where($field, '>', $info[$field])
                ->where($starMap)
                ->count();
            $rank = (int)bcadd($rank, 1);
        }
        $info['rank']     = $rank;
        $info['count']    = $info[$field];
        $info['num']      = (int)bcdiv($sumTime, self::TIMER);
        $info['img']      = $headWear['img'];
        $info['headwear'] = HeadwearUser::getUse($info['user_id']);

        return $info;
    }

    private static function getMyPkRankList($rankTypeField, $user_id, $extra)
    {
        $headWear = CfgHeadwear::where('key', CfgHeadwear::PK)->find();
        $pkStatus = (new Pk())->getPkStatus();
        if ($pkStatus['status'] < 2) {
            // 正在报名 上一场数据
            $lastPkTime = Db::name('pk_settle')->where('is_settle', 1)->order('id desc')->value('pk_time');
        } elseif ($pkStatus['status'] == 2) {
            // 团战开始 当前场数据
            $lastPkTime = date('Y-m-d', time()) . ' ' . $pkStatus['timeSpace']['start_time'] . ':00';
        }

        $infoMap = ['uid' => $user_id];
        if ($rankTypeField == 'today') {
            $infoMap['last_pk_time'] = $lastPkTime;
        }
        $info = PkUserRank::with(['User', 'star'])->where($infoMap)->find();
        if (empty($info)) return null;

        $sumTime = self::where('user_id', $user_id)
            ->where('type', self::PK)
            ->value('sum_time', 0);

        $field = $rankTypeField == 'today' ? 'last_pk_count' : 'achievement_total_count';
        $rank  = 'no';
        if ($info[$field] > 0) {
            $rankMap = [
                $field => ['>', $info[$field]]
            ];
            if ($rankTypeField == 'star') {
                $rankMap['mid'] = $extra['star_id'];
            }

            $rank = PkUserRank::where($rankMap)->count();
            $rank = (int)bcadd($rank, 1);
        }
        $info['rank']     = $rank;
        $info['count']    = $info[$field];
        $info['headwear'] = HeadwearUser::getUse($user_id);
        $info['img']      = $headWear['img'];
        $item['num']      = (int)bcdiv($sumTime, self::TIMER);

        return $info;
    }

    private static function getMyNewGuyRankList($rankTypeField, $user_id, $extra)
    {
        $rankMap = [
            'today'     => date('Y-m-d') . ' 00:00:00',
            'yesterday' => date('Y-m-d', strtotime('-1 days')) . ' 00:00:00',
            'week'      => date('Y-m-d H:i:s', strtotime('monday this week')),
            'month'     => date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00',
        ];

        $rankEnd = [];
        if ($rankTypeField == 'yesterday') {
            $rankEnd['yesterday'] = $rankMap['today'];
        }

        $user = User::where('id', $user_id)->find();
        if ($rankMap[$rankTypeField] > $user['create_time']) {
            return null;
        }
        if (array_key_exists($rankTypeField, $rankEnd)) {
            if ($rankEnd[$rankTypeField] < $user['create_time']) {
                return null;
            }
        }

        $info = UserStar::with(['User', 'Star'])->where('user_id', $user_id)->find();
        if (empty($info)) {
            Common::res(['code' => 1, 'msg' => '请先加入圈子']);
        }

        $fieldMap = [
            'today'     => 'thisday_count',
            'yesterday' => 'lastday_count',
            'week'      => 'achievement_week_count',
            'month'     => 'achievement_month_count',
        ];

        $field = $fieldMap[$rankTypeField];

        $headWear = CfgHeadwear::where('key', CfgHeadwear::NEW_GUY)->find();

        $sumTime = self::where('user_id', $user_id)
            ->where('type', self::NEW_GUY)
            ->value('sum_time', 0);

        $rank = 'no';
        if ($info[$field] > 0) {
            $rank = UserStar::where($field, '>', $info[$field])->count();
            $rank = (int)bcadd($rank, 1);
        }
        $info['rank']     = $rank;
        $info['count']    = $info[$field];
        $info['headwear'] = HeadwearUser::getUse($user_id);
        $info['img']      = $headWear['img'];
        $item['num']      = (int)bcdiv($sumTime, self::TIMER);

        return $info;
    }

    public static function getRankByTypeForAchievement($type, $rankType, $page = 1, $size = 10, $extra = [])
    {
        switch ($type) {
            case self::FLOWER_TIME:
                $list = self::getFlowerTimeRankList($rankType, $page, $size, $extra);
                break;
            case self::FLOWER:
                $list = self::getFlowerRankList($rankType, $page, $size, $extra);
                break;
            case self::PK:
                $list = self::getPkRankList($rankType, $page, $size, $extra);
                break;
            case self::NEW_GUY:
                $list = self::getNewGuyRankList($rankType, $page, $size, $extra);
                break;
            default:
                break;
        }

        return empty($list) ? [] : $list;
    }

    private static function getPkRankList($rankType, $page, $size, array $extra)
    {
        $headWear = CfgHeadwear::where('key', CfgHeadwear::PK)->find();

        $fieldMap = [
            'today' => 'last_pk_count',
            'star'  => 'achievement_total_count',
            'all'   => 'achievement_total_count',
        ];
        $field    = $fieldMap[$rankType];
        $map      = [
            $field => ['>', 0],
        ];
        if ($rankType == 'today') {
            $pkStatus = (new Pk())->getPkStatus();
            if ($pkStatus['status'] < 2) {
                // 正在报名 上一场数据
                $lastPkTime = Db::name('pk_settle')->where('is_settle', 1)->order('id desc')->value('pk_time');
            } elseif ($pkStatus['status'] == 2) {
                // 团战开始 当前场数据
                $lastPkTime = date('Y-m-d', time()) . ' ' . $pkStatus['timeSpace']['start_time'] . ':00';
            }
            $map['last_pk_time'] = $lastPkTime;
        }
        $order = [
            $field             => 'desc',
            'orderupdate_time' => 'asc'
        ];
        $list = PkUserRank::with(['User', 'star'])
            ->where($map)
            ->order($order)
            ->page($page, $size)
            ->select();

        $userIds         = array_column($list, 'uid');
        $achievementDict = self::getDictList(new UserAchievementHeal(), $userIds, 'user_id', "*", ['type' => self::PK]);

        foreach ($list as $key => $value) {
            $value['headwear'] = HeadwearUser::getUse($value['uid']);
            $value['img']      = $headWear['img'];
            $value['count']    = $value[$field];
            $value['num']      = 0;
            if (array_key_exists($value['uid'], $achievementDict)) {
                $value['num'] = (int)bcdiv($achievementDict[$value['uid']]['sum_time'], self::TIMER);
            }

            $list[$key] = $value;
        }

        return empty($list) ? [] : $list;
    }

    private static function getNewGuyRankList($rankType, $page, $size, array $extra)
    {
        $rankMap = [
            'today'     => date('Y-m-d') . ' 00:00:00',
            'yesterday' => date('Y-m-d', strtotime('-1 days')) . ' 00:00:00',
            'week'      => date('Y-m-d H:i:s', strtotime('monday this week')),
            'month'     => date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00',
        ];
        $orderMap = [
            'today'     => 'thisday_count',
            'yesterday' => 'lastday_count',
            'week'      => 'achievement_week_count',
            'month'     => 'achievement_month_count',
        ];

        $headWear = CfgHeadwear::where('key', CfgHeadwear::NEW_GUY)->find();
        $map      = [
            'create_time' => ['>', $rankMap[$rankType]]
        ];
        if ($rankType == 'yesterday') {
            $end                = $rankMap['today'];
            $map['create_time'] = ['between', [$rankMap[$rankType], $end]];
        }
        $order = [
            $orderMap[$rankType] => 'desc',
            'update_time'        => 'desc',
            'id'                 => 'asc'
        ];
        $list  = UserStar::with(['User', 'Star'])
            ->page($page, $size)
            ->where($map)
            ->order($order)
            ->select();
        if (is_object($list)) $list = $list->toArray();

        $userIds         = array_column($list, 'user_id');
        $achievementDict = self::getDictList(new UserAchievementHeal(), $userIds, 'user_id', "*", ['type' => self::NEW_GUY]);
        foreach ($list as $index => $item) {
            $item['count']    = $item[$orderMap[$rankType]];
            $item['headwear'] = HeadwearUser::getUse($item['user_id']);
            $item['img']      = $headWear['img'];
            $item['num']      = 0;
            if (array_key_exists($item['user_id'], $achievementDict)) {
                $item['num'] = (int)bcdiv($achievementDict[$item['user_id']]['sum_time'], self::TIMER);
            }

            $list[$index] = $item;
        }

        return $list;
    }

    private static function getFlowerRankList($rankType, $page, $size, array $extra)
    {
        $starListMap = [
            'today'     => 'day_flower',
            'yesterday' => 'yesterday_flower',
            'star'      => 'achievement_flower',
            'all'       => 'achievement_flower',
        ];
        $headWear    = CfgHeadwear::where('key', CfgHeadwear::FLOWER)->find();
        if (array_key_exists($rankType, $starListMap)) {
            $whereField = $starListMap[$rankType];

            $order = [
                $whereField => 'desc'
            ];
            if ($rankType == 'yesterday') {
                $order += ['yesterday_flower_time' => 'desc'];
            } else {
                $order += [
                    'update_time' => 'asc',
                    'id'          => 'asc'
                ];
            }

            $map = [
                $whereField => ['>', 0],
            ];
            if ($rankType == 'star') {
                $map += ['star_id' => $extra['star_id']];
            }
            $list = UserStar::with(['User', 'Star'])
                ->page($page, $size)
                ->where($map)
                ->order($order)
                ->select();

            if (is_object($list)) $list = $list->toArray();

            $userIds         = array_column($list, 'user_id');
            $achievementDict = self::getDictList(new UserAchievementHeal(), $userIds, 'user_id', "*", ['type' => self::FLOWER]);

            foreach ($list as $index => $item) {
                $value             = $item;
                $value['count']    = $item[$whereField];
                $value['img']      = $headWear['img'];
                $value['headwear'] = HeadwearUser::getUse($item['user_id']);
                $value['num']      = 0;
                if (array_key_exists($item['user_id'], $achievementDict)) {
                    $value['num'] = (int)bcdiv($achievementDict[$item['user_id']]['sum_time'], self::TIMER);
                }

                $list[$index] = $value;
            }
        }

        return empty($list) ? [] : $list;
    }

    /**
     * @param       $rankType
     * @param int   $page
     * @param int   $size
     * @param array $extra
     * @return array|bool|false|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    private static function getFlowerTimeRankList($rankType, $page = 1, $size = 10, $extra = [])
    {
        $map = ['type' => self::FLOWER_TIME];
        switch ($rankType) {
            case "today":
                $order = [
                    'invite_day'  => 'desc',
                    'invite_sum'  => 'desc',
                    'invite_time' => 'asc',
                    'id'          => 'asc'
                ];
                break;
            case "star":
                $map   += ['star_id' => $extra['star_id']];
                $order = [
                    'invite_sum'  => 'desc',
                    'invite_time' => 'asc',
                    'id'          => 'asc'
                ];
                break;
            case "all":
                $order = [
                    'invite_sum'  => 'desc',
                    'invite_time' => 'asc',
                    'id'          => 'asc'
                ];
                break;
            default:
                break;
        }

        if (empty($order)) {
            return [];
        }

        $field = $rankType == 'today' ? 'invite_day' : 'invite_sum';

        $list = self::with(['user', 'star'])
            ->where($map)
            ->where($field, '>', 0)
            ->order($order)
            ->page($page, $size)
            ->select();
        if (is_object($list)) $list = $list->toArray();

        $headWear = CfgHeadwear::where('key', CfgHeadwear::FLOWER_TIME)->find();
        foreach ($list as $key => $value) {
            $item             = $value;
            $item['count']    = $value[$field];
            $item['num']      = (int)bcdiv($value['sum_time'], self::TIMER);
            $item['img']      = $headWear['img'];
            $item['headwear'] = HeadwearUser::getUse($value['user_id']);

            $list[$key] = $item;
        }

        return $list;
    }

    /**
     * 设置用户存储的时间
     *
     * @param $user_id
     * @param $star_id
     * @param $time
     * @param $type
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function recordTime($user_id, $star_id, $time, $type)
    {
        $map   = compact('user_id', 'star_id', 'type');
        $exist = (new self)->readMaster()->where($map)->find();
        if (is_object($exist)) $exist = $exist->toArray();
        if (empty($exist)) {
            self::create(array_merge($map, [
                'sum_time'   => $time,
                'count_time' => $time,
            ]));
        } else {
            $data = [
                'sum_time'   => bcadd($time, $exist['sum_time']),
                'count_time' => bcadd($time, $exist['count_time']),
            ];
            self::where('id', $exist['id'])->update($data);
        }
    }

    public static function getAchievement($user_id, $type)
    {
        $map   = compact('user_id', 'type');
        $exist = (new self)->readMaster()->where($map)->find();
        if (empty($exist)) return false;

        $last    = bcsub($exist['count_time'], self::TIMER);
        $data    = ['count_time' => $last];
        $updated = self::where('id', $exist['id'])->update($data);
        if (empty($updated)) return false;

        return 1;
    }

    /**
     * 返回是否可以领取头饰状态
     *
     * @param $user_id
     * @param $type
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function checkStatus($user_id, $type)
    {
        $status = false;
        $num    = 0;
        $map    = compact('user_id', 'type');
        $exist  = self::where($map)->find();

        if ($exist) {
            $status = $exist['count_time'] >= UserAchievementHeal::TIMER;
            $num    = $status ? bcdiv($exist['count_time'], UserAchievementHeal::TIMER) : 0;
        }

        return ['status' => $status, 'num' => $num];
    }

    public static function getAchievementReward($user_id, $task_id)
    {
        $task = CfgTaskgift::get($task_id);
        if (empty($task)) {
            return false;
        }

        $reward = json_decode($task['awards'], true);

        $res = CfgTaskgift::getAchievementStatus($reward, $user_id);
        if (empty($res['status'])) {
            return false;
        }

        $able = HeadwearUser::checkHasAchievement($user_id, self::$typeMap[$reward['achievement']]);
        if (empty($able)) {
            Common::res(['code' => 1, 'msg' => '您已经兑换过一个了']);
        }

        $status = false;
        Db::startTrans();
        try {
            $num = self::getAchievement($user_id, $reward['achievement']);
            if (empty($num)) {
                Common::res(['code' => 1, 'msg' => '您还未达到领取条件哦']);
            }

            $status = HeadwearUser::getAchievement($user_id, $num, self::$typeMap[$reward['achievement']]);
            if (empty($status)) {
                Common::res(['code' => 1, 'msg' => '您还未达到领取条件哦']);
            }

//            throw new Exception('something was wrong');
            Db::commit ();
        } catch (Throwable $exception) {
            Db::rollback ();
//            throw $exception;
            Common::res(['code' => 1, 'msg' => '您还未达到领取条件哦']);
        }

        return $status;
    }

    /**
     * @param Model  $model
     * @param array  $ids
     * @param string $key
     * @param string $fields
     * @param array  $map
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getDictList(Model $model, array $ids, $key, $fields = '*', $map = [])
    {
        $list = $model->where($key, 'in', $ids)
            ->where($map)
            ->field($fields)
            ->select();
        if (is_object($list)) $list = $list->toArray();
        return array_column($list, null, $key);
    }
}