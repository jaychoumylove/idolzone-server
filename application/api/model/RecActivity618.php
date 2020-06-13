<?php

namespace app\api\model;

use app\base\model\Base;

class RecActivity618 extends Base
{

    public static function getList($uid, $page, $size, $filter = '')
    {
        if ($filter) {
            $logList = self::where('user_id', $uid)->where('content', 'like', '%' . $filter . '%')->order('id desc')->page($page, $size)->select();
        } else {
            $logList = self::where('user_id', $uid)->order('id desc')->page($page, $size)->select();
        }

        return $logList;
    }
    /**存入日志 */
    public static function addRec($log)
    {
        // 每个用户最多保存1000条记录
        $user_id = $log['user_id'];
        $count = self::where('user_id', $user_id)->count('id');
        if ($count >= 1000) {
            $id = self::where('user_id', $user_id)->min('id');
            self::where('id', $id)->delete(true);
        }

        return self::create($log);
    }
}
