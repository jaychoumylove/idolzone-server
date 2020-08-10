<?php


namespace app\api\model;


class UserOccupy extends \app\base\model\Base
{
    const STATUS_CONTINUE = '_CONTINUE';
    const STATUS_BREAK = '_BREAK';

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function star()
    {
        return $this->hasOne('Star', 'id', 'star_id');
    }

    /**
     * @param     $type
     * @param int $page
     * @param int $size
     * @param     $extra
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRankByTypeForAchievement($type, $page = 1, $size = 10, $extra = [])
    {
        switch ($type) {
            case "today":
                $map = 'day_top_time > 0';
                $map .= ' or top_status = "' . self::STATUS_CONTINUE . '"';
                $order = [
                    'day_top_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "star":
                $map = '(sum_top_time > 0 and star_id = ' . $extra['star_id'] . ')';
                $map .=' or top_status = "' . self::STATUS_CONTINUE . '"';
                $order = [
                    'sum_top_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "all":
                $map = 'sum_top_time > 0';
                $map .=' or top_status = "' . self::STATUS_CONTINUE . '"';
                $order = [
                    'sum_top_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            default:
                break;
        }

        if (empty($order) || empty($map)) {
            return [];
        }

        $list = self::with(['user', 'star'])->where($map)->order ($order)->page ($page, $size)->select ();
        if (is_object ($list)) $list = $list->toArray ();

//        $able = Cfg::checkOccupyTime ();
//        if (empty($able)) return $list;

        foreach ($list as $key => $value) {
            if ($value['top_status'] == self::STATUS_CONTINUE) {
                $currentTime = time ();
                $diffTime = bcsub ($currentTime, (int)$value['top_time']);
                $value['day_top_time'] = bcsub ((int)$value['day_top_time'], $diffTime);
                $value['sum_top_time'] = bcsub ((int)$value['sum_top_time'], $diffTime);
            }

            $value['count'] = $value['day_top_time'];
            $value['num'] = (int)bcdiv ($value['sum_top_time'], 60*60*24);

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
            'top_status' => self::STATUS_CONTINUE
        ];
        $stopper = $model->where($map)->find ();
        if (empty($stopper)) return false;

        $currentTime = time ();

        $endTime = $currentTime;
        if (date ('YmdH', $stopper['top_time']) != date ('YmdH', $currentTime)) {
            // 不在同一个小时内的表示
            $endTime = (int) strtotime (date ('Y-m-d H', $stopper['top_time']) . ":00:00");
        }

        $diffTime = bcsub ($endTime, (int)$stopper['top_time']);
        $updated = [
            'top_status' => self::STATUS_BREAK,
            'day_top_time' => bcsub ((int)$stopper['day_top_time'], $diffTime),
            'sum_top_time' => bcsub ((int)$stopper['sum_top_time'], $diffTime),
        ];

        $model->where($map)->update($updated);
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
            'top_time' => $currentTime
        ];
        if ($occupier) {
            $model->where ($map)->update($data);
        } else {
            $model::create (array_merge ($data, $map));
        }
    }
}