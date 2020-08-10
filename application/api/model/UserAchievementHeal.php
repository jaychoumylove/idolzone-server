<?php


namespace app\api\model;


class UserAchievementHeal extends \app\base\model\Base
{
    const STATUS_CONTINUE = '_CONTINUE';
    const STATUS_BREAK = '_BREAK';

    const FLOWER_TIME = 'flower_time';
    const NEW_GUY = 'newguy';
    const FLOWER = 'flower';
    const PK = 'pk';

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
        var_dump ($type, $rankType);;
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
            $list = [];
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
            'day' => date ('Y-m-d') . ' 00:00:00',
            'yesterday' => date ('Y-m-d', strtotime ('-1 days')) . ' 00:00:00',
            'week' => date ('Y-m-d H:i:s', strtotime('monday this week')),
            'month' => date ('Y-m-d', strtotime('first day of this month')) . ' 00:00:00',
        ];

        $orderMap = [
            'day' => 'thisday_count',
            'yesterday' => 'lastday_count',
            'week' => 'thisweek_count',
            'month' => 'thismonth_count',
        ];

        $orderField = 'userStar.' . $orderMap[$rankType];

        return User::with(['userStar', 'achievement'])
            ->where('create_time', '>', $rankMap[$rankType])
            ->order ([
                 $orderField => 'desc',
                 'create_time' => 'asc',
                 'id' => 'asc'
            ])
            ->page ($page, $size)
            ->select ();
    }

    private static function getFlowerRankList($rankType, $page, $size, array $extra)
    {
        $starListMap = [
            'today' => 'day_flower',
            'yesterday' => 'yesterday_flower',
        ];
        if (array_key_exists ($rankType, $starListMap)) {
            $whereField = $starListMap[$rankType];
            $list = UserStar::with(['User', 'Star', 'achievement'])
                ->page ($page, $size)
                ->order ([
                    $whereField => 'desc',
                    'create_time' => 'asc',
                    'id' => 'asc'
                ])
                ->select ();
        }

        $listMap = ['star', 'all'];
        if (in_array ($rankType, $listMap)) {
            $map = ['type' => self::FLOWER];
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
                $value['day_time'] = bcsub ((int)$value['day_time'], $diffTime);
                $value['sum_time'] = bcsub ((int)$value['sum_time'], $diffTime);
                $value['count_time'] = bcsub ((int)$value['count_time'], $diffTime);
            }

            $value['count'] = $value['day_time'];
            $value['num'] = (int)bcdiv ($value['sum_time'], 60*60*24);

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
        $model = self::readMaster ();

        $map = [
            'top_status' => self::STATUS_CONTINUE,
            'type' => self::FLOWER_TIME
        ];
        $stopper = $model->where($map)->find ();
        if (empty($stopper)) return false;

        $diffTime = self::supportDiffTimer ((int)$stopper['top_time']);
        $updated = [
            'top_status' => self::STATUS_BREAK,
            'day_time' => bcsub ((int)$stopper['day_time'], $diffTime),
            'sum_time' => bcsub ((int)$stopper['sum_time'], $diffTime),
            'count_time' => bcsub ((int)$stopper['count_time'], $diffTime),
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
            $endTime = (int) strtotime (date ('Y-m-d H', $timer) . ":00:00");
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

        $model = self::readMaster ();

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
}