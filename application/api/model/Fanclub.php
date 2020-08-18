<?php
namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use app\api\service\User as UserService;
use think\Db;
use think\model\Relation;

class Fanclub extends Base
{

    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,head_img_s,name');
    }
    
    public function user()
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
        if ($field == 'fansclub_count') {
            $list = Fanclub::with('star')->where($w)
                ->whereOr('clubname', 'like', '%' . $keyword . '%')
                ->order('week_count desc')
                ->page($page, $size)
                ->select();
        } else 
            if ($field == 'fansclub_hot') {
                $list = Fanclub::with('star')->where($w)
                    ->whereOr('clubname', 'like', '%' . $keyword . '%')
                    ->order('week_hot desc')
                    ->page($page, $size)
                    ->select();
            } else 
                if ($field == 'star_hot') {
                    $list = Db::name('fanclub')->alias('f')
                        ->join('star s', 's.id = f.star_id')
                        ->field('s.name as clubname,s.head_img_s as avatar,f.star_id,sum(mem_count) as mem_count,sum(week_count) as week_count,sum(week_hot) as week_hot')
                        ->where($w)
                        ->group('f.star_id')
                        ->page($page, $size)
                        ->order('week_hot desc')
                        ->select();
                }
        
        return $list;
    }

    /**
     * 加入粉丝团
     */
    public static function joinFanclub($uid, $f_id)
    {
        if (UserStar::getStarId($uid) != Fanclub::where('id', $f_id)->value('star_id')) {
            Common::res([
                'code' => 1,
                'msg' => '不能加入所属其他爱豆的粉丝团'
            ]);
        }
        
        $isExist = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($isExist)
            Common::res([
                'code' => 1,
                'msg' => '你已加入了一个粉丝团'
            ]);
            
            // 是否有退团记录
        $hasExited = Db::name('fanclub_user')->where('user_id', $uid)
            ->order('delete_time desc')
            ->value('delete_time');
        
        Db::startTrans();
        try {
            
            // 没有退团才记录
            if (! $hasExited) {
                
                // 查找推荐人
                $rer_user_id = UserRelation::where('ral_user_id', $uid)->value('rer_user_id');
                
                if ($rer_user_id)
                    FanclubUser::where([
                        'user_id' => $rer_user_id,
                        'fanclub_id' => $f_id
                    ])->update([
                        'weekmem_count' => Db::raw('weekmem_count+1')
                    ]);
                
                $selfupdate['weekmem_count'] = Db::raw('weekmem_count+1');
            }
            
            // 更新粉丝团人数
            $selfupdate['mem_count'] = Db::raw('mem_count+1');
            self::where('id', $f_id)->update($selfupdate);
            
            FanclubUser::create([
                'user_id' => $uid,
                'fanclub_id' => $f_id
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
     * 提/降管理员
     */
    public static function upAdmin($operater,$uid,$admin=0){
        $fanclubId=FanclubUser::where('user_id', $uid)->value('fanclub_id');
        $isLeader = FanclubUser::isLeader($operater);
        if ($operater != $uid && ! $isLeader) {
            Common::res(['code' => 1, 'msg' => '没有权限']);
        }
        if($admin==1){
            $adminCount=FanclubUser::where(['fanclub_id'=>$fanclubId,'admin'=>1])->count();
            if($adminCount >=2) Common::res(['msg'=>'只可设置两个管理员']);
        }

        $res=FanclubUser::where('user_id',$uid)->update(['admin'=>$admin]);
        return $res;
    }
    /**
     * 退出粉丝团
     */
    public static function exitFanclub($operater, $uid, $force = false)
    {
        $fanclubUser = FanclubUser::where('user_id', $uid)->field('fanclub_id,week_count,week_hot,weekmem_count,weekbox_count')->find();
        $isLeader = FanclubUser::isLeader($operater);
        $isAdmin= FanclubUser::isAdmin($operater);
        if (($isLeader || $isAdmin  )==false && $operater != $uid) {
            Common::res([
                'code' => 1,
                'msg' => '没有权限'
            ]);
        }
        
//         $hasExited = Db::name('fanclub_user')->where('user_id', $uid)
//             ->order('delete_time desc')
//             ->value('delete_time');
        // if (strtotime($delete_time) > time() - 3600 * 24 * 3) {
        // Common::res(['code' => 1, 'msg' => '三天之内不能再次退出粉丝团']);
        // }
        
        if ($fanclubUser['fanclub_id']) {
            Db::startTrans();
            try {
                // 用户退出
                FanclubUser::destroy(['user_id' => $uid]);
                FanclubApplyUser::where(['user_id' => $uid])->delete();
                    
                self::where('id', $fanclubUser['fanclub_id'])->update([
                    'mem_count' => Db::raw('mem_count-1'),
                    'week_count' => Db::raw('week_count-'.$fanclubUser['week_count']),
                    'week_hot' => Db::raw('week_hot-'.$fanclubUser['week_hot']),
                    'weekmem_count' => Db::raw('weekmem_count-'.$fanclubUser['weekmem_count']),
                    'weekbox_count' => Db::raw('weekbox_count-'.$fanclubUser['weekbox_count'])
                ]);
                
                if ($isLeader && $operater==$uid) {
                    $user_id = FanclubUser::where([
                        'fanclub_id' => $fanclubUser['fanclub_id']
                    ])->order('week_count desc')->value('user_id');
                    
                    // 转交团长
                    if($user_id) self::where('id', $fanclubUser['fanclub_id'])->update(['user_id' => $user_id]);
                    
                    // 销毁粉丝团
                    else self::destroy(['user_id' => $uid]);
                          
                }

                // 退过的需要100钻石
//                 if ($operater==$uid && $hasExited)
//                     (new UserService())->change($uid, ['stone' => - 100], '超过1次退出粉丝团');
                
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
     * 粉丝团贡献度增加
     */
    public static function change($uid, $hot)
    {
        $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($fid != 0) {
            Db::startTrans();
            try {
                self::where('id', $fid)->update([
                    'week_count' => Db::raw('week_count+' . $hot),
                    'month_count' => Db::raw('month_count+' . $hot)
                ]);
                
                FanclubUser::where('user_id', $uid)->update([
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

    public static function removeAll($operater, array $uids)
    {
        $isLeader = FanclubUser::isLeader($operater);
        $isAdmin= FanclubUser::isAdmin($operater);
        if (($isLeader || $isAdmin  )==false) {
            Common::res([
                'code' => 1,
                'msg' => '没有权限'
            ]);
        }
        $fanclubId =  FanclubUser::where('user_id', $operater)->value ('fanclub_id');
        $fanclubUsers = FanclubUser::where('user_id', 'in', $uids)
            ->where('fanclub_id', $fanclubId)
            ->field('user_id,week_count,week_hot,weekmem_count,weekbox_count')
            ->select();
        if (is_object ($fanclubUsers)) $fanclubUsers = $fanclubUsers->toArray ();

        $user_ids = array_column ($fanclubUsers, 'user_id');
        if (empty($user_ids)) {
            Common::res (['code' => 1, 'msg' => '用户已请出']);
        }

        $fanclub = self::get ($fanclubId);
        if (empty($fanclub)) {
            Common::res (['code' => 1, 'msg' => '粉丝团已注销']);
        }


        $weekCount    = array_sum (array_column ($fanclubUsers, 'week_count'));
        $weekHot      = array_sum (array_column ($fanclubUsers, 'week_hot'));
        $weekMemCount = array_sum (array_column ($fanclubUsers, 'weekmem_count'));
        $weekBoxCount = array_sum (array_column ($fanclubUsers, 'weekbox_count'));

        $fanClubUpdate = [
            'mem_count'    => bcsub ($fanclub['mem_count'], count ($user_ids)),
            'week_count'    => bcsub ($fanclub['week_count'], $weekCount),
            'week_hot'      => bcsub ($fanclub['week_hot'], $weekHot),
            'weekmem_count' => bcsub ($fanclub['weekmem_count'], $weekMemCount),
            'weekbox_count' => bcsub ($fanclub['weekbox_count'], $weekBoxCount),
        ];

        Db::startTrans ();
        try {
            // 用户退出
            FanclubUser::destroy (['user_id' => ['in', $user_ids]]);
            FanclubApplyUser::where('user_id', 'in', $user_ids)->delete();

            self::where('id', $fanclubId)->update($fanClubUpdate);

            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();

            Common::res (['code' => 1, 'msg' => '请稍后再试']);
        }
    }

}
