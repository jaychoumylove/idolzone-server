<?php


namespace app\api\model;


use app\api\controller\v1\Pk;
use think\Db;

class UserAchievementHeal extends \app\base\model\Base
{
    const STATUS_CONTINUE = '_CONTINUE';
    const STATUS_BREAK = '_BREAK';

    const TIMER = 86400; // 60*60*24

    const FLOWER_TIME = 'flower_time';
    const NEW_GUY = 'newguy';
    const FLOWER = 'flower';
    const PK = 'pk';

    public static $typeMap = [
        self::FLOWER_TIME => CfgHeadwear::FLOWER_TIME,
        self::NEW_GUY => CfgHeadwear::NEW_GUY,
        self::FLOWER => CfgHeadwear::FLOWER,
        self::PK => CfgHeadwear::PK
    ];

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function userStar()
    {
        return $this->hasOne ('UserStar', 'user_id', 'user_id');
    }

    public function star()
    {
        return $this->hasOne('Star', 'id', 'star_id');
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

        return empty($list) ? []: $list;
    }

    private static function getPkRankList($rankType, $page, $size, array $extra)
    {
        if ($rankType == 'today') {
            $pkStatus = (new Pk())->getPkStatus();

            if ($pkStatus['status'] < 2) {
                // 正在报名 上一场数据
                $lastPkTime = Db::name('pk_settle')->where('is_settle', 1)->order('id desc')->value('pk_time');
            } elseif ($pkStatus['status'] == 2) {
                // 团战开始 当前场数据
                $lastPkTime = date('Y-m-d', time()) . ' ' . $pkStatus['timeSpace']['start_time'] . ':00';
            }
            $list = Db::name('pk_user_rank')->alias('pk')
                ->join('user u', 'u.id = pk.uid','LEFT')
                ->where('pk.last_pk_time', $lastPkTime)
                ->order('pk.last_pk_count desc,pk.orderupdate_time asc')->page($page, 10)
                ->field('pk.*,u.avatarurl,u.nickname as name')->select();

            foreach ($list as &$value) {
                $value['headwear'] = HeadwearUser::getUse($value['uid']);
            }
        }

        $listMap = ['star', 'all'];
        if (in_array ($rankType, $listMap)) {
            $map = ['type' => self::PK];
            if ($rankType == 'star') {
                $map['star_id'] = $extra['star_id'];
            }

            $list = self::with (['user', 'star', 'userStar'])
                ->where ($map)
                ->order ([
                    'sum_time' => 'desc',
                    'id' => 'asc'
                ])
                ->page ($page, $size)
                ->select ();
        }

        return $list;
    }

    private static function getNewGuyRankList($rankType, $page, $size, array $extra)
    {
        $rankMap = [
            'today'     => date ('Y-m-d') . ' 00:00:00',
            'yesterday' => date ('Y-m-d', strtotime ('-1 days')) . ' 00:00:00',
            'week'      => date ('Y-m-d H:i:s', strtotime ('monday this week')),
            'month'     => date ('Y-m-d', strtotime ('first day of this month')) . ' 00:00:00',
        ];

        $orderMap = [
            'today'     => 'thisday_count',
            'yesterday' => 'lastday_count',
            'week'      => 'thisweek_count',
            'month'     => 'thismonth_count',
        ];

        $orderField = 'us.' . $orderMap[$rankType];

        $list = Db::name('user_star')->alias('us')
            ->join('user u', 'u.id = us.user_id')
            ->where('u.create_time', '>', $rankMap[$rankType])
            ->order([
                $orderField  => 'desc',
                'u.id' => 'asc'
            ])
            ->page($page, $size)
            ->field('us.*,u.avatarurl,u.nickname as name')
            ->select();
        if (is_object ($list)) $list = $list->toArray ();

        $userIds = array_column ($list, 'user_id');
        $users = User::where('id', 'in', $userIds)->field('id,nickname,avatarurl')->select ();
        if (is_object ($users)) $users = $users->toArray ();
        $userDict = array_column ($users, null, 'id');

        $starIds = array_column ($list, 'star_id');
        $stars = Star::where('id', 'in', $starIds)->select ();
        if (is_object ($stars)) $stars = $stars->toArray ();
        $starDict = array_column ($stars, null, 'id');
        foreach ($list as $index => $item) {
            $user = array_key_exists ($item['user_id'], $userDict) ? $userDict[$item['user_id']]: null;
            $star = array_key_exists ($item['star_id'], $starDict) ? $starDict[$item['star_id']]: null;
            $item['user'] = $user;
            $item['star'] = $star;
            $item['count'] = $item[$orderMap[$rankType]];

            $list[$index] = $item;
        }

        return $list;
    }

    private static function getFlowerRankList($rankType, $page, $size, array $extra)
    {
        $starListMap = [
            'today' => 'day_flower',
            'yesterday' => 'yesterday_flower',
        ];
        if (array_key_exists ($rankType, $starListMap)) {
            $whereField = $starListMap[$rankType];

            $order = [
                $whereField => 'desc'
            ];
            if ($rankType == 'today') {
                $order += ['yesterday_flower' => 'desc'];
            }
            $order += [
                'create_time' => 'asc',
                'id' => 'asc'
            ];
            $list = UserStar::with(['User', 'Star', 'achievement'])
                ->page ($page, $size)
                ->order ($order)
                ->select ();

            if (is_object ($list)) $list = $list->toArray ();

            foreach ($list as $index => $item) {
                $item['count'] = $item[$whereField];
                $list[$index] = $item;
            }
        }

        $listMap = ['star', 'all'];
        if (in_array ($rankType, $listMap)) {
            $map = ['ua.type' => self::FLOWER];
            if ($rankType == 'star') {
                $map['ua.star_id'] = $extra['star_id'];
            }

            $list = Db::name('user_star')->alias('us')
                ->join('user_achievement_heal ua', 'ua.user_id = us.user_id')
                ->where ($map)
                ->order([
                    'ua.sum_time'  => 'desc',
                    'us.total_flower'  => 'desc',
                    'ua.id' => 'asc'
                ])
                ->page($page, $size)
                ->field('ua.*,us.total_flower')
                ->select();
            if (is_object ($list)) $list = $list->toArray ();

            $userIds = array_column ($list, 'user_id');
            $users = User::where('id', 'in', $userIds)->field('id,nickname,avatarurl')->select ();
            if (is_object ($users)) $users = $users->toArray ();
            $userDict = array_column ($users, null, 'id');
            foreach ($list as $index => $item) {
                $user = array_key_exists ($item['user_id'], $userDict) ? $userDict[$item['user_id']]: null;
                $list[$index]['user'] = $user;
            }
        }

        return empty($list) ? []: $list;
    }

    /**
     * @param       $rankType
     * @param int   $page
     * @param int   $size
     * @param array $extra
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function getFlowerTimeRankList($rankType, $page = 1, $size = 10, $extra = [])
    {
        $map = ['type' => self::FLOWER_TIME];
        switch ($rankType) {
            case "today":
                $order = [
                    'day_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "star":
                $map += ['star_id' => $extra['star_id']];
                $order = [
                    'sum_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "all":
                $order = [
                    'sum_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            default:
                break;
        }

        if (empty($order)) {
            return [];
        }

        $list = self::with(['user', 'star'])
            ->where($map)
            ->order ($order)
            ->page ($page, $size)
            ->select ();
        if (is_object ($list)) $list = $list->toArray ();

//        $able = Cfg::checkOccupyTime ();
//        if (empty($able)) return $list;

        foreach ($list as $key => $value) {
            if ($value['top_status'] == self::STATUS_CONTINUE) {
                $diffTime = self::supportDiffTimer ((int)$value['top_time']);
                $value['day_time'] = bcadd ((int)$value['day_time'], $diffTime);
                $value['sum_time'] = bcadd ((int)$value['sum_time'], $diffTime);
                $value['count_time'] = bcadd ((int)$value['count_time'], $diffTime);
            }

            $value['count'] = $value['day_time'];
            $value['num'] = (int)bcdiv ($value['sum_time'], self::TIMER);

            $list[$key] = $value;
        }

        return $list;
    }

    /**
     * 老将退场
     *
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function occupyStop()
    {
        $model = (new self)->readMaster ();

        $map = [
            'top_status' => self::STATUS_CONTINUE,
            'type' => self::FLOWER_TIME
        ];
        $stopper = $model->where($map)->find ();
        if (empty($stopper)) return false;

        $diffTime = self::supportDiffTimer ((int)$stopper['top_time']);
        $updated = [
            'top_status' => self::STATUS_BREAK,
            'day_time' => bcadd ((int)$stopper['day_time'], $diffTime),
            'sum_time' => bcadd ((int)$stopper['sum_time'], $diffTime),
            'count_time' => bcadd ((int)$stopper['count_time'], $diffTime),
        ];

        $model->where($map)->update($updated);
    }

    /**
     * @param $timer
     * @return string
     */
    public static function supportDiffTimer($timer)
    {
        $currentTime = time ();

        $endTime = $currentTime;
        if (date ('YmdH', $timer) != date ('YmdH', $currentTime)) {
            // 不在同一个小时内的表示
            $endTime = (int) strtotime (date ('Y-m-d H', $timer) . ":59:59");
        }

        return bcsub ($endTime, (int)$timer);
    }

    /**
     * 新王登基
     *
     * @param     $user_id
     * @param int $star_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function occupyStart($user_id, $star_id = 0)
    {
//        $able = Cfg::checkOccupyTime ();
//        if (empty($able)) return false;

        if (empty($star_id)) $star_id = UserStar::getStarId ($user_id);

        $model = (new self)->readMaster ();

        $map = compact ('user_id', 'star_id');
        $occupier = $model->where ($map)->find ();

        $currentTime = time ();

        $data = [
            'top_status' => self::STATUS_CONTINUE,
            'top_time' => $currentTime,
            'type' => self::FLOWER_TIME
        ];
        if ($occupier) {
            $model->where ($map)->update($data);
        } else {
            $model::create (array_merge ($data, $map));
        }
    }

    /**
     * 设置用户存储的时间
     * @param $user_id
     * @param $star_id
     * @param $time
     * @param $type
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function recordTime($user_id, $star_id, $time, $type)
    {
        $map = compact ('user_id', 'star_id', 'type');
        $exist = self::where($map)->find ();
        if (is_object ($exist)) $exist = $exist->toArray ();
        if (empty($exist)) {
            self::create (array_merge ($map, [
                'day_time' => $time,
                'sum_time' => $time,
                'count_time' => $time,
            ]));
        } else {
            $data = [
                'day_time' => bcadd ($time, $exist['day_times']),
                'sum_time' => bcadd ($time, $exist['sum_time']),
                'count_time' => bcadd ($time, $exist['count_time']),
            ];
            self::where('id', $exist['id'])->update($data);
        }
    }

    public static function getAchievement($user_id, $type)
    {
        $map = compact ('user_id', 'type');
        $exist = self::where($map)->find ();
        if (empty($exist)) return false;

        $last = bcmod ($exist['count_time'], self::TIMER);
        $num = bcdiv ($exist['count_time'], self::TIMER);
        $data = ['count_time' => $last];
        $updated = self::where('id', $exist['id'])->update($data);
        if (empty($updated)) return false;

        return $num;
    }

    public static function getAchievementTask($user_id)
    {
        // 拿到所有任务
        // 拿到所有奖励
        // 检查是否可领取
        $task = self::where(compact ('user_id'))->select ();
        if (is_object ($task)) $task = $task->toArray ();
        $taskDict = array_column ($task, null, 'type');

        $reward = CfgHeadwear::where('key', 'in', array_values (self::$typeMap))->select ();
        if (is_object ($reward)) $reward = $reward->toArray ();
        $rewardDict = array_column ($reward, null, 'key');

        $list = [];
        $default = [
            'user_id' => $user_id,
            'star_id' => UserStar::getStarId ($user_id),
            'top_time' => 0,
            'day_time' => 0,
            'sum_time' => 0,
            'count_time' => 0,
            'top_status' => UserAchievementHeal::STATUS_BREAK,
        ];
        foreach (self::$typeMap as $index => $item) {
            $single = array_key_exists ($index, $taskDict) ? $taskDict[$index]: array_merge ($default, ['type' => $index]);
            $single['status'] = $single['count_time'] >= UserAchievementHeal::TIMER;
            $single['over'] = $single['status'];
            $single['btn_text'] = $single['status'] ? "领取": "未达成";
            if (array_key_exists ($item, $rewardDict)) {
                $single = array_merge ($single, ['img' => $rewardDict[$item]['img']]);
            }

            array_push ($list, $single);
        }

        return $list;
    }
}