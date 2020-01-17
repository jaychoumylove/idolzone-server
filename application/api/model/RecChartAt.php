<?php

namespace app\api\model;

use app\base\model\Base;

class RecChartAt extends Base
{
    /**提取@用户并保存 */
    public static function getAt($content, $chart_id)
    {
        foreach (explode('@', $content) as $value) {
            $username = trim($value);
            if ($username) {
                $arr[] = $username;
            }
        }

        while (true) {
            $pos = strpos($content, '@');
            // strpos($content, '@');
        }

        $uids = User::where('nickname', 'in', $arr)->column('id');

        foreach ($uids as $uid) {
            self::create([
                'user_id' => $uid,
                'chart_id' => $chart_id
            ]);
        }
    }
}
