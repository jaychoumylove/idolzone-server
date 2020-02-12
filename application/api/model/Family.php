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
            ->order('thisweek_count desc')
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
                    'thisweek_count' => Db::raw('thisweek_count+' . $hot)
                ]);
                
                FamilyUser::where('user_id', $uid)->update([
                    'thisweek_count' => Db::raw('thisweek_count+' . $hot)
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
        if (time() > $family_switch['reback_end_time'])
            Common::res([
                'code' => 1,
                'msg' => '活动已结束'
            ]);
        
        if (FamilyUser::where('user_id', $uid)->whereTime('settle_time', 'week')->value('count(1)'))
            Common::res([
                'code' => 1,
                'msg' => '本周已领取'
            ]);
            
            // 计算出排名
        $user = FamilyUser::where('user_id', $uid)->field('family_id,lastweek_count')->find();
        $lastweek_count = Family::where('id', $user['family_id'])->value('lastweek_count');
        $lastweek_rank = Family::where('lastweek_count', '>', $lastweek_count)->count() + 1;
        $lastweek_rank = $lastweek_rank > 11 ? 11 : $lastweek_rank;
        
        if (! $user['lastweek_count'] || ! $lastweek_count)
            Common::res([
                'code' => 1,
                'msg' => '上周无贡献，无法领取'
            ]);
            
            // 计算出收益
        $cfgEarn = CfgFamily::where('id', $lastweek_rank)->field('name,coin,stone')->find();
        $earn['coin'] = ceil($user['lastweek_count'] * $cfgEarn['coin']);
        $earn['stone'] = $cfgEarn['stone'];
        
        Db::startTrans();
        try {
            
            FamilyUser::where('user_id', $uid)->update([
                'settle_time' => date('Y-m-d H:i:s')
            ]);
            
            (new UserService())->change($uid, $earn, $cfgEarn['name']);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
        
        return $earn;
    }
}
