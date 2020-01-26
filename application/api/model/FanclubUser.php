<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class FanclubUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function fanclub()
    {
        return $this->belongsTo('Fanclub', 'fanclub_id', 'id')->field('id,user_id,clubname,avatar');
    }

    /**是否是团长 */
    public static function isLeader($uid)
    {
        $fid = self::where('user_id', $uid)->value('fanclub_id');
        $f_user = Fanclub::where('id', $fid)->value('user_id');
        return $f_user == $uid;
    }
    
    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $fid, $field)
    {
        $where = $fid ? ['user_id' => $uid, 'fanclub_id' => $fid] : ['user_id' => $uid];
        $res = self::where($where)->field('user_id,'.$field)->find();
        
        $res['score'] = $res[$field];
        
        if ($res['score']) {
             $where = $fid ? ['fanclub_id' => $fid] : '1=1';
             $res['rank'] = self::where($where)->where($field, '>', $res['score'])->count() + 1;
        } else {
            $res['rank'] = '未上榜';
        }        
        $res['hasExited'] = Db::name('fanclub_user')->where('user_id', $uid)->where('delete_time','NOT NULL')->value('delete_time');
            
        // 等级
        $res['level'] = CfgUserLevel::getLevel($uid);
        return $res;
    }
}
