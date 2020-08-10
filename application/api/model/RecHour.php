<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class RecHour extends Base
{
    public function User()
    {
        return $this->belongsTo('User')->field('id,nickname,avatarurl');
    }

    public function Star()
    {
        return $this->belongsTo('Star')->field('id,name,head_img_l');
    }

    /**小时榜第一 */
    public static function getTop()
    {
        $time = date('YmdH');

        return self::with('User,Star')->where('time', $time)->order('count desc,id asc')->find();
    }

    /**小时榜排名 */
    public static function getRankList($page = 1, $size = 10)
    {
        $currentTime = time ();
        $time = date('YmdH', $currentTime);

        $list = self::with('User,Star')->where('time', $time)->order('count desc,id asc')->page($page, $size)->select();
        foreach ($list as $key => $value) {
            if ($page == 1 && $key < 1) {
                $diffTime = bcsub ($currentTime, $value['top_time']);
                $value['top_minute'] = bcdiv ($diffTime, 60);
            }
            $user = $value['user'];
            $user['level'] = CfgUserLevel::getLevel($value['user_id']);
            $user['headwear'] = HeadwearUser::getUse($value['user_id']);

            $value['user'] = $user;
            $list[$key] = $value;
        }

        return $list;
    }

    public static function getRankInfo($user_id, $star_id)
    {
        $time = date('YmdH');
        $map = compact ('user_id', 'star_id', 'time');

        $count = self::where ($map)->value ('count');

        if (empty($count)) return 0;

        return (int)$count;
    }

    /**
     * 小时榜用户贡献增加
     *
     * @param $user_id
     * @param $hot
     * @param $star_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function change($user_id, $hot, $star_id)
    {
        $time = date('YmdH');

        $map = compact ('user_id', 'time');

        $model = (new self())->readMaster ();
        $info = $model->where ($map)->find ();

        $basicCount = empty($info) ? 0: $info['count'];
        $count = bcadd ($basicCount, $hot);

        $data['count'] = $count;

        $top = $model->where('time', $time)->order ([
            'count' => 'desc',
            'id' => 'asc'
        ])->find ();

        $becomeTop = false;
        if (empty($top)) {
            // 无人占领，自动上位
            $becomeTop = true;
        } else {
            if ($top['user_id'] != $user_id && (int)$top['count'] < $count) {
                // 成功上位
                $becomeTop = true;
            }
        }

        if ($becomeTop) {
            $data['top_time'] = time ();
        }

        if (empty($info)) {
            $data['star_id'] = $star_id;
            $created = array_merge ($data, $map);
            $model::create ($created);
        } else {
            $model->where ('id', $info['id'])->update($data);
        }

        if ($becomeTop) {
            UserOccupy::occupyStop ();
            UserOccupy::occupyStart ($user_id, $star_id);
        }
    }
}
