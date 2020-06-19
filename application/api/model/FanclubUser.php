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
    /**是否管理员 */
    public static function isAdmin($uid)
    {
        $fid = self::where('user_id', $uid)->value('admin');
        if($fid===0){
            return false;
        }else{
            return true;
        }
    }
    
    /**我在圈子里的排名信息 */
    public static function getMyRankInfo($uid, $fid, $field)
    {
        $where = $fid ? ['user_id' => $uid, 'fanclub_id' => $fid] : ['user_id' => $uid];
        $res = self::where($where)->field('user_id,last'.$field.' as lastweek_score,'.$field.' as score')->find();
                
        if ($res['score']) {
            
             $where = $fid ? ['fanclub_id' => $fid] : '1=1';
             $res['rank'] = self::where($where)->where($field, '>', $res['score'])->count() + 1;
        } 
        else $res['rank'] = 'no';
        
        $res['hasExited'] = '';//Db::name('fanclub_user')->where('user_id', $uid)->where('delete_time','NOT NULL')->value('delete_time');
            
        // 等级
        $res['level'] = CfgUserLevel::getLevel($uid);
        return $res;
    }

    public static function addActiveDragonBoatFestivalHot($uid,$addHot){
        if(Cfg::isActiveDragonBoatFestivalStart()==false)return;
        $fanclub_id=self::where('user_id',$uid)->value('fanclub_id');
        if(!$fanclub_id)return;
        self::where('user_id',$uid)->update([
            'dragon_boat_festival_hot'=> Db::raw('dragon_boat_festival_hot+'.$addHot),
        ]);
        ActiveDragonBoatFestivalFanclub::where('fanclub_id',$fanclub_id)->update([
            'active_time'=> time(),
        ]);
    }
}
