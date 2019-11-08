<?php

namespace app\api\model;

use app\base\model\Base;

class Rec extends Base
{
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public static function getList($uid, $page, $size)
    {
        $logList = self::where('user_id', $uid)->order('id desc')->page($page, $size)->select();

        return $logList;
    }

    /**存入日志 */
    public static function addRec($log)
    {
        // 每个用户最多保存100条记录 TODO:
        // $user_id = $log['user_id'] ;
        


        return self::create($log);
    }
}
