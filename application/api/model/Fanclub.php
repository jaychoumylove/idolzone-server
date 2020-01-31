<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use app\api\service\User as UserService;
use think\Db;

class Fanclub extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,head_img_s,name');
    }

    public static function getList($keyword, $field, $page, $size)
    {
        // 关键字
        if ($keyword) {
            $ids = Star::where('name', 'like', '%' . $keyword . '%')->column('id');
            $w = ['star_id' => ['in', $ids]];
        } else {
            $w = '1=1';
        }

        // 字段排序
        if ($field == 'fansclub_count') {
            $list = Fanclub::with('star')->where($w)->whereOr('clubname', 'like', '%' . $keyword . '%')
                ->order('week_count desc')->page($page, $size)->select();
        } else if ($field == 'fansclub_hot') {
            $list = Fanclub::with('star')->where($w)->whereOr('clubname', 'like', '%' . $keyword . '%')
                ->order('week_hot desc')->page($page, $size)->select();
        } else if ($field == 'star_hot') {
            $list = Db::name('fanclub')->alias('f')->join('star s', 's.id = f.star_id')
                ->field('s.name as clubname,s.head_img_s as avatar,f.star_id,sum(mem_count) as mem_count,sum(week_count) as week_count,sum(week_hot) as week_hot')
                ->where($w)->group('f.star_id')->page($page, $size)->order('week_hot desc')->select();
        }

        return $list;
    }

    /**加入粉丝团 */
    public static function joinFanclub($uid, $f_id)
    {
        if (UserStar::getStarId($uid) != Fanclub::where('id', $f_id)->value('star_id')) {
            Common::res(['code' => 1, 'msg' => '不能加入所属其他爱豆的粉丝团']);
        }

        $isExist = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($isExist) Common::res(['code' => 1, 'msg' => '你已加入了一个粉丝团']);
        
        Db::startTrans();
        try {        
            self::where('id', $f_id)->update([
                'mem_count' => Db::raw('mem_count+1')
            ]);
    
            FanclubUser::create([
                'user_id' => $uid,
                'fanclub_id' => $f_id,
            ]);
            
            RecTaskfanclub::addRec($f_id, [1,2,3]);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    /**退出粉丝团 */
    public static function exitFanclub($uid,$force=false)
    {
        $hasExited = Db::name('fanclub_user')->where('user_id', $uid)->order('delete_time desc')->value('delete_time');
//         if (strtotime($delete_time) > time() - 3600 * 24 * 3) {
//             Common::res(['code' => 1, 'msg' => '三天之内不能再次退出粉丝团']);
//         }

        $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        $leader = FanclubUser::isLeader($uid);

        if ($fid) {
            Db::startTrans();
            try {
                // 用户退出
                if($force) FanclubUser::where(['user_id' => $uid])->delete();
                else FanclubUser::destroy(['user_id' => $uid]);

                self::where('id', $fid)->update([
                    'mem_count' => Db::raw('mem_count-1')
                ]);

                if ($leader) {
                    $user_id = FanclubUser::where(['fanclub_id' => $fid])->order('thisweek_count desc')->value('user_id');
                    if ($user_id === null) {
                        // 销毁粉丝团
                        if($force) self::where(['user_id' => $uid])->delete();
                        else self::destroy(['user_id' => $uid]);
                        
                    } else {
                        // 转交团长
                        self::where('id', $fid)->update(['user_id' => $user_id]);
                    }
                }
                
                RecTaskfanclub::addRec($fid, [1,2,3],-1);

                //退过的需要100钻石
                if($hasExited) (new UserService)->change($uid, ['stone' => -100], '退出粉丝团，钻石-100');

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                Common::res(['code' => 400, 'msg' => $e->getMessage()]);
            }
        }
    }

    /**粉丝团贡献度增加 */
    public static function change($uid, $hot)
    {
        $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        if ($fid != 0) {
            self::where('id', $fid)->update([
                'week_count' => Db::raw('week_count+' . $hot),
                'month_count' => Db::raw('month_count+' . $hot)
            ]);

            FanclubUser::where('user_id', $uid)->update(['thisweek_count' => Db::raw('thisweek_count+' . $hot)]);
        }
    }
}
