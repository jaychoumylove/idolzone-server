<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class FamilyUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function family()
    {
        return $this->belongsTo('Family', 'family_id', 'id')->field('id,user_id,name,avatar');
    }

    /**是否是族长 */
    public static function isLeader($uid)
    {
        $fid = self::where('user_id', $uid)->value('family_id');
        $f_user = Family::where('id', $fid)->value('user_id');
        return $f_user == $uid;
    }
    
    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $fid, $field)
    {
        $where = $fid ? ['user_id' => $uid, 'family_id' => $fid] : ['user_id' => $uid];
        $res = self::where($where)->field('user_id,'.$field)->find();
        
        $res['score'] = $res[$field];
        
        if ($res['score']) {
             $where = $fid ? ['family_id' => $fid] : '1=1';
             $res['rank'] = self::where($where)->where($field, '>', $res['score'])->count() + 1;
        } else {
            $res['rank'] = 'no';
        }        
        $res['hasExited'] = Db::name('family_user')->where('user_id', $uid)->where('delete_time','NOT NULL')->value('delete_time');
            
        // 等级
        $res['level'] = CfgUserLevel::getLevel($uid);
        return $res;
    }
}
