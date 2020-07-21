<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use app\api\model\UserSprite;
use app\api\model\User as UserModel;
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
                break;
            case 4:
                $complete = UserSprite::where('user_id', $uid)->value('total_speed_coin');
                break;
            case 5:
                $complete = UserStar::where('user_id',$uid)->value('like_count');
                break;
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
    
    public static function getMaxBadge($uid, $stype){
        $data = [];
        $data = self::where(['uid'=>$uid,'stype'=>$stype])->order('badge_id desc')->field('badge_id,stype,left(create_time, 11) as complete_time')->find();
        if($data) $data['simg'] = CfgBadge::where('id',$data['badge_id'])->value('simg');
        
        return $data;        
    }
    
    /**排行 */
    public static function getRank($starid, $stype, $page, $size = 10)
    {
        $field = [1=>'pick_days',2=>'total_flower',3=>'fanclub_mass_times',4=>'total_speed_coin',5=>'like_count',6=>'gold'];
        $where = $starid ? ['st.star_id'=>$starid] : '1=1';
        
        switch ($stype) {
            case 4://农场产量
                $list = UserSprite::alias('sp')->join('user_star st','sp.user_id=st.user_id','LEFT')->join('user_ext ex','ex.user_id=st.user_id','LEFT')->where('ex.badge_id','>',0)->where($where)->where([$field[$stype] => ['neq', 0]])->order($field[$stype] . ' desc,st.total_count desc')->field("*,{$field[$stype]} as hot")->page($page, $size)->select();
                break;
                
            case 6://pk金牌
                $list = PkUserRank::alias('sp')->join('user_star st','sp.uid=st.user_id','LEFT')->join('user_ext ex','ex.user_id=st.user_id','LEFT')->where('ex.badge_id','>',0)->where($where)->where([$field[$stype] => ['neq', 0]])->order($field[$stype] . ' desc,st.total_count desc')->field("*,{$field[$stype]} as hot")->page($page, $size)->select();
                break;
                
            default:
                $list = UserStar::alias('st')->join('user_ext ex','ex.user_id=st.user_id','LEFT')->where('ex.badge_id','>',0)->where($where)->where([$field[$stype] => ['neq', 0]])->order($field[$stype] . ' desc,st.total_count desc')->field("*,{$field[$stype]} as hot")->page($page, $size)->select();
                break;                
        }
        
        foreach ($list as $key => &$value) {
            $value['user'] = UserModel::where('id',$value['user_id'])->field('id,nickname,avatarurl')->find();
            if(!isset($value['user']) || !isset($value['user']['id'])) continue;
            $value['user']['maxBadge'] = self::getMaxBadge($value['user']['id'],$stype);
            if(!$value['user']['maxBadge']) unset($list[$key]);
        }
        return array_values($list);
    }
    
    //初始化徽章
    public static function initBadge($uid)
    {
        $regTime = strtotime(User::where(['id'=>$uid])->value('create_time'));
        if(date('Ymd')==date('Ymd',$regTime)) return;

        $done = UserExt::where(['user_id'=>$uid])->update(['badge_id'=>2]);
        if(!$done) return;
        
        //打榜徽章，按注册日期  1
        $days = (int)(time() - $regTime) / 86400;
        UserStar::where(['user_id'=>$uid])->order('total_count desc')->update(['pick_days'=>Db::raw('pick_days+' . $days)]);
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

    /**
     * 获取徽章排名 数字越大排名越高
     * @param $user_id
     * @param $type
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserTypeBadgeOffset($user_id, $type)
    {
        $userBadges = self::where('stype', $type)
            ->where('user_id', $user_id)
            ->select ();

        if (is_object ($userBadges)) $userBadges = $userBadges->toArray ();

        if (empty($userBadges)) {
            return 0;
        }

        $userBadgeIds = array_column ($userBadges, 'badge_id');

        $cfgBadge = CfgBadge::where('stype', $type)
            ->order ('count', 'desc')
            ->select ();

        if (is_object ($cfgBadge)) $cfgBadge = $cfgBadge->toArray ();

        $count = count($cfgBadge);
        foreach ($cfgBadge as $key => $value) {
            if (in_array ($value['id'], $userBadgeIds)) {
                $maxKey = bcadd ($key, 1);
                break;
            }
        }

        return bcsub ($count, $maxKey);
    }
    
}
