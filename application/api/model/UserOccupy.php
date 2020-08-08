<?php


namespace app\api\model;


class UserOccupy extends \app\base\model\Base
{
    const STATUS_CONTINUE = 'CONTINUE';
    const STATUS_BREAK = 'BREAK';

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
                $map .= ' or top_status = ' . self::STATUS_CONTINUE;
                $order = [
                    'day_top_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "star":
                $map = '(sum_top_time > 0 and star_id = ' . $extra['star_id'] . ')';
                $map .= " or top_status = " . self::STATUS_CONTINUE;
                $order = [
                    'sum_top_time' => 'desc',
                    'id' => 'asc'
                ];
                break;
            case "all":
                $map = 'sum_top_time > 0';
                $map .= " or top_status = " . self::STATUS_CONTINUE;
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

        $list = self::where($map)->page ($page, $size)->order ($order)->select ();
        if (is_object ($list)) $list = $list->toArray ();

        $able = Cfg::checkOccupyTime ();
        if (empty($able)) return $list;

        foreach ($list as $key => $value) {
            if ($value['top_status'] == self::STATUS_CONTINUE) {
                $currentTime = time ();
                $diffTime = bcsub ($currentTime, (int)$value['top_time']);
                $value['day_top_time'] = bcsub ((int)$value['day_top_time'], $diffTime);
                $value['sum_top_time'] = bcsub ((int)$value['sum_top_time'], $diffTime);
            }

            $list[$key] = $value;
        }

        return $list;
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function occupyStop()
    {
        $able = Cfg::checkOccupyTime ();
        if (empty($able)) return false;

        $model = self::readMaster ();

        $map = [
            'top_status' => self::STATUS_CONTINUE
        ];
        $stopper = $model->where($map)->find ();

        $currentTime = time ();

        $diffTime = bcsub ($currentTime, (int)$stopper['top_time']);
        $updated = [
            'top_status' => self::STATUS_BREAK,
            'day_top_time' => bcsub ((int)$stopper['day_top_time'], $diffTime),
            'sum_top_time' => bcsub ((int)$stopper['sum_top_time'], $diffTime),
        ];

        $model->where($map)->update($updated);
    }
}