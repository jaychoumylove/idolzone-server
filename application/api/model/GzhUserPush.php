<?php

namespace app\api\model;

use app\base\model\Base;

class GzhUserPush extends Base
{
    /**推送列表 */
    public static function getList($uid)
    {   
        $cfgList = CfgGzhPush::all();
        $myList = GzhUserPush::where('user_id', $uid)->column('push_id');

        foreach ($cfgList as &$value) {
            if (in_array($value['id'], $myList)) {
                $value['check'] = true;
            }
        }

        return $cfgList;
    }

    /**添加订阅项 */
    public static function setData($uid, $push_id, $checked = true)
    {
        if ($checked) {
            self::create([
                'user_id' => $uid,
                'push_id' => $push_id
            ]);
        } else {
            self::where('user_id', $uid)->where('push_id', $push_id)->delete();
        }
    }
}
