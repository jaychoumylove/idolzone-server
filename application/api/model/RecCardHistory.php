<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;
use think\Model;

class RecCardHistory extends Base
{
    /**应援结算 */
    public static function settle()
    {
        // 转存数据
        $res = Db::query("SELECT s.name,count(u.id) as count 
        FROM `f_user_star` u join f_star s on s.id = u.star_id where u.active_card_days >= 7 and u.delete_time is null 
        GROUP BY u.star_id ORDER BY count desc LIMIT 10;");

        self::create([
            'date' => date('Y年m月', time() - 3600),
            'value' => json_encode($res, JSON_UNESCAPED_UNICODE)
        ]);

        // 清除数据
        UserStar::where('1=1')->update([
            'active_card_days' => 0,
            'active_card_time' => 0,
            'active_newbie_cards' => 0,
        ]);

        // 修改活动时间
        $firstday = strtotime(date('Y-m-01'));
        $lastday = strtotime(date('Y-m-01') . ' +1 month ');
        Cfg::where('key', 'active_date')->update([
            'value' => "[$firstday,$lastday]"
        ]);
    }
}
