<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use app\base\service\Common;

class UserContribute extends Base
{
    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id');
    }

    public static function getRank($starid, $field, $page, $size)
    {
        if ($starid) {
            $w = ['star_id' => $starid];
        } else {
            $w = '1=1';
        }

        return self::with('User')->where($w)->order($field . ' desc')->page($page, $size)->select();
    }

    public static function change($uid, $starid, $hot)
    {
        $item = self::get(['user_id' => $uid, 'star_id' => $starid]);
        if ($item) {
            self::where(['user_id' => $uid, 'star_id' => $starid])->update([
                'total_count' => Db::raw('total_count+' . $hot),
                'thisweek_count' => Db::raw('thisweek_count+' . $hot),
                'thismonth_count' => Db::raw('thismonth_count+' . $hot),
            ]);
        } else {
            Common::res(['code' => 1, 'msg' => '未加入该圈子']);
        }
    }
}
