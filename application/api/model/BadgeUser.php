<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Model;
use think\Db;

class BadgeUser extends Base
{

    public function CfgBadge()
    {
        return $this->belongsTo('CfgBadge', 'badge_id','id')->field('id,name,text,count,bimg,simg,gopage,add_count,end_time');
    }
    
    /**正在使用的头饰 */
    public static function getUse($uid)
    {
        $badges = self::with('CfgBadge')->where('uid', $uid)->where('status', 1)->order('update_time asc')->select();
        $res = [];
        foreach ($badges as $key=>$value){
            $res[$key]['simg'] = $value['cfg_badge']['simg'];
        }
        return $res;
    }
    
    public static function cancel($uid, $badge_id)
    {
        self::where('uid', $uid)->where('badge_id', $badge_id)->update(['status' => 0]);
    }

    public static function use($uid, $stype, $badge_id)
    {
        $useCount = self::where(['uid'=>$uid,'status' =>1])->group('stype')->select();
        if(count($useCount) > 6){
            Common::res(['code' => 400, 'msg' => '佩戴的徽章数不能超过7个']);
        }
        else{
            self::where(['uid'=>$uid,'stype'=>$stype,'status'=>1])->update(['status' => 0]);
            self::where(['uid'=>$uid,'badge_id'=>$badge_id])->update(['status' => 1]);
        }
    }
    
    /**
     * 增加徽章记录（进度） 
     * @param int $uid
     * @param int $stype 徽章小类
     * @param int $count 达标值
     * @param int $badge_id 指定徽章
     */
    public static function addRec($uid, $stype, $count, $badge_id=0)
    {
        $where = $badge_id ? ['id'=>$badge_id] : ['stype'=>$stype];
        $badges = CfgBadge::where($where)->where('count<='.$count)->field('id,stype')->select();

        foreach ($badges as $badge) {
            try {
                
                self::create([
                    'uid' => $uid,
                    'badge_id' => $badge['id'],
                    'stype' => $badge['stype']
                ]);
                
            } catch (\Exception $e) {}
            
        }
    }

    /**
     * 任务完成进度
     */
    public static function getUserComplete($uid, $stype)
    {
        $complete = 0;
        switch ($stype) {
            case 1:
                $complete = UserStar::where('user_id',$uid)->value('pick_days');
                break; 
            case 2:
                $complete = UserStar::where('user_id',$uid)->value('total_flower');
                break;
            case 3:
                $complete = UserStar::where('user_id',$uid)->value('fanclub_mass_times');
            case 4:
                $complete = UserSprite::where('user_id', $uid)->value('total_speed_coin');
            case 5:
                $complete = UserStar::where('user_id',$uid)->value('like_count');                
            case 6:
                $complete = Db::name('pk_user_rank')->where('uid',$uid)->order('last_pk_time desc')->value('gold');
                break;
            default:
                break;
        }
        return $complete;
    }
    
    //农场临时加速
    public static function speedUp($uid)
    {
        $timenow = date('Y-m-d H:i:s');
        
        $data = self::alias('b')
            ->join('cfg_badge c','b.badge_id=c.id','LEFT')
            ->where('b.stype',7)
            ->where('b.uid',$uid)
            ->where('c.end_time','>=',$timenow)
            ->value('sum(c.add_count)');
        
        $data = $data ? $data : 0;
        return $data;
    }
    
    //初始化徽章
    public static function initBadge($uid)
    {
        $regTime = strtotime(User::where(['id'=>$uid])->value('create_time'));
        if(date('Ymd')==date('Ymd',$regTime)) return;
        
        $done = UserExt::where(['user_id'=>$uid])->update(['badge_id'=>1]);
        if(!$done) return;
        
        //打榜徽章，按注册日期  1
        $days = (int)(time() - $regTime) / 86400;
        BadgeUser::addRec($uid, 1, $days);
        
        //农场徽章 4
        $count = UserSprite::where(['user_id'=>$uid])->value('total_speed_coin');
        $count = $count ? $count : 0;
        BadgeUser::addRec($uid, 4, $count);
        
        //点赞徽章 5
        $count = UserStar::where(['user_id'=>$uid])->order('total_count desc')->value('like_count');
        $count = $count ? $count : 0;
        BadgeUser::addRec($uid, 5, $count);
        
        //团战徽章6
        $count = Db::name('pk_user_rank')->where(['uid'=>$uid])->order('last_pk_time desc')->value('gold');
        $count = $count ? $count : 0;
        BadgeUser::addRec($uid, 6, $count);
    }
    
}
