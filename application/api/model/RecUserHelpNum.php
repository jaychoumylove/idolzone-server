<?php


namespace app\api\model;

use think\Db;

class RecUserHelpNum extends \app\base\model\Base
{

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,nickname,avatarurl');
    }

    public static function getList($uid, $page, $size)
    {
        $logList = self::where('user_id', $uid)->order('id desc')->page($page, $size)->select();
        $logList = json_decode(json_encode($logList, JSON_UNESCAPED_UNICODE), true);
        foreach ($logList as &$value){
            $value['content'] = '';
            if($value['type'] == 'coin_flower'){
                $value['content'] = '贡献人气获得助力值';
            }else if($value['type'] == 'flower'){
                $value['content'] = '打榜送鲜花获得助力值';
            }else if($value['type'] == 'panacea'){
                $value['content'] = '使用灵丹获得助力值';
            }else if($value['type'] == 'lukey_prop'){
                $value['content'] = '使用幸运抽奖券获得助力值';
            }else if($value['type'] == 'scrap'){
                $value['content'] = '使用幸运碎片获得助力值';
            }else if($value['type'] == 'old'){
                $value['content'] = '老用户福利';
            }
        }

        return $logList;
    }

}