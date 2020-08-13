<?php
namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use app\api\service\User as UserService;
use think\Db;

class Family extends Base
{

    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,head_img_s,name');
    }

    public function leader()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public static function getList($keyword, $field, $page, $size)
    {
        // 关键字
        if ($keyword) {
            $ids = Star::where('name', 'like', '%' . $keyword . '%')->column('id');
            $w = [
                'star_id' => [
                    'in',
                    $ids
                ]
            ];
        } else {
            $w = '1=1';
        }
        
        // 字段排序
        $list = self::with('star')->where($w)
            ->whereOr('clubname', 'like', '%' . $keyword . '%')
            ->field('id,avatar,clubname,mem_count,star_id,user_id,'.$field.' as hot')
            ->order($field.' desc')
            ->page($page, $size)
            ->select();
        return $list;
    }

    /**
     * 家族 上线
     */
    public static function getMaxMen($f_id, $leader_id = 0)
    {
        if (! $leader_id)
            $leader_id = Family::where('id', $f_id)->value('user_id');
        $allow_count = BadgeUser::where([
            'uid' => $leader_id,
            'stype' => 2
        ])->value('count(1)');
        return $allow_count;
    }

    /**
     * 加入家族
     */
    public static function joinFamily($uid, $f_id)
    {
        if (UserStar::getStarId($uid) != self::where('id', $f_id)->value('star_id')) {
            Common::res([
                'code' => 1,
                'msg' => '不能加入所属其他爱豆的家族'
            ]);
        }
        
        $isExist = FamilyUser::where('user_id', $uid)->value('family_id');
        if ($isExist)
            Common::res([
                'code' => 1,
                'msg' => '每人只能加入一个家族'
            ]);
        
        $men_count = self::where('id', $f_id)->value('mem_count');
        if ($men_count >= self::getMaxMen($f_id))
            Common::res([
                'code' => 1,
                'msg' => '该家族已满员'
            ]);
        
        Db::startTrans();
        try {
            self::where('id', $f_id)->update([
                'mem_count' => Db::raw('mem_count+1')
            ]);
            
            FamilyUser::create([
                'user_id' => $uid,
                'family_id' => $f_id
            ]);

            // 更新状态为1，表示成功拉新一次 此时上级可以领取奖励
            UserRelation::where(['ral_user_id' => $uid])->update(['status' => 1]);
            $starId = UserStar::getStarId ($uid);
            UserInvite::recordInvite ($uid, $starId);
            \app\api\service\Star::addInvite ($starId);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * 退出家族
     */
    public static function exitFamily($operater, $uid, $force = false)
    {
        $familyUser = FamilyUser::where('user_id', $uid)->field('family_id,thisweek_count')->find();
        if(!isset($familyUser['family_id'])) return;
        
        $isLeader = FamilyUser::isLeader($operater);
            
        if ($operater != $uid && ! $isLeader) {
            Common::res([
                'code' => 1,
                'msg' => '没有权限'
            ]);
        }
        
        Db::startTrans();
        try {
            if ($operater == $uid && $isLeader){//销毁家族
                self::where('user_id',$uid)->delete();
                FamilyUser::where('family_id', $familyUser['family_id'])->delete();
                //(new UserService())->change($uid, [], '销毁家族');
            }
            else{
                // 用户退出
                FamilyUser::where('user_id', $uid)->delete();
                
                self::where('id', $familyUser['family_id'])->update([
                    'mem_count' => Db::raw('mem_count-1'),
                    'thisweek_count' => Db::raw('thisweek_count-' . $familyUser['thisweek_count'])
                ]);
                //(new UserService())->change($uid, [], '退出家族');
            }
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * 家族贡献度增加
     */
    public static function change($uid, $hot)
    {
        $family_switch = Cfg::where('key', 'family_switch')->value('value');
        $family_switch = json_decode($family_switch, true);
        if(time()<$family_switch['start_time'] || time()>$family_switch['end_time'] ) return;
        
        $fid = FamilyUser::where('user_id', $uid)->value('family_id');
        if ($fid != 0) {
            Db::startTrans();
            try {
                self::where('id', $fid)->update([
                    'day_count' => Db::raw('day_count+' . $hot),
                    'week_count' => Db::raw('week_count+' . $hot)
                ]);
                
                FamilyUser::where('user_id', $uid)->update([
                    'day_count' => Db::raw('day_count+' . $hot),
                    'week_count' => Db::raw('week_count+' . $hot)
                ]);
                
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res([
                    'code' => 400,
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * 家族结算
     */
    public static function settle($uid)
    {
        $family_switch = Cfg::where('key', 'family_switch')->value('value');
        $family_switch = json_decode($family_switch, true);
        if (time() > $family_switch['reback_end_time'])  Common::res(['code' => 1,'msg' => '活动已结束']);
        
        $whereTime = $family_switch['field'] == 'day_count' ? 'today' : 'week';
        if (FamilyUser::where('user_id', $uid)->whereTime('settle_time', $whereTime)->count()) Common::res(['code' => 1,'msg' => $family_switch['field_lastname'].'奖励已领取过了，请到日志中查看']);
            
            // 计算出排名
        $user = FamilyUser::where('user_id', $uid)->field('family_id,last'.$family_switch['field'].' as hot')->find();
        $lastHot = Family::where('id', $user['family_id'])->value('last'.$family_switch['field']);   
        if (! $user['hot'] || ! $lastHot)  Common::res(['code' => 1,'msg' => $family_switch['field_lastname'].'无贡献，无法领取']);
            
            // 计算出收益
        $lastRank = Family::where('last'.$family_switch['field'], '>', $lastHot)->count() + 1;
        $cfgEarn = CfgFamily::where('field',$family_switch['field'])->where('rank','<=', $lastRank)->order('rank desc')->field('name,coin,stone')->find();
        $earn['coin'] = ceil($user['hot'] * $cfgEarn['coin']);
        $earn['stone'] = $cfgEarn['stone'];
        
        Db::startTrans();
        try {
            
            $settle_time = $family_switch['field'] == 'day_count' ? date('Y-m-d',strtotime('today')) : date('Y-m-d',strtotime('this week'));
            $isDone = FamilyUser::where('settle_time IS NULL OR settle_time < "'.$settle_time.'"')->where('user_id', $uid)->update([
                'settle_time' => date('Y-m-d H:i:s')
            ]);
            if (!$isDone) Common::res(['code' => 1,'msg' => $family_switch['field_lastname'].'奖励已领取过了，请到日志中查看']);
            
            (new UserService())->change($uid, $earn, $cfgEarn['name']);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400,'msg' => $e->getMessage()]);
        }
        
        return $earn;
    }
}
