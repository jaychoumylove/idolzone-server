<?php


namespace app\api\model;


use app\base\model\Base;
use think\Db;

class StarManor extends Base
{
    public function star()
    {
        return $this->hasOne('Star', 'id', 'star_id');
    }

    public static function addSum($count, $uid)
    {
        $starId = UserStar::getStarId($uid);
        if ($starId) {
            $map = ['star_id' => $starId];
            $starManor = self::get($map);
            $status = Cfg::checkConfigTime(Cfg::MANOR_NATIONAL_DAY);
            if ($starManor) {
                $data = [
                    'sum' => Db::raw('sum+'.$count)
                ];
                if ($status) {
                    $allActiveSum = (new UserManor)->readMaster()
                        ->where($map)
                        ->limit(100)
                        ->column('active_sum');
                    $data['active_count'] = (int)array_sum($allActiveSum);
                }
                self::where($map)->update($data);
            } else {
                $data = [
                    'sum' => $count,
                    'active_count' => 0
                ];
                if ($status) {
                    $data['active_count'] = $count;
                }
                self::create(array_merge($data, $map));
            }
        }
    }
}