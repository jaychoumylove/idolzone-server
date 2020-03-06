<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class FanclubBoxUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**打开宝箱 */
    public static function openBox($uid, $box_id)
    {
        $isExist = self::where('box_id', $box_id)->where('user_id', $uid)->value('id');
        if ($isExist) return;
        
        $boxUser = Db::name('fanclub_box_user')->where('box_id', $box_id)->where('delete_time IS NOT NULL')->limit(1)->find();
        
        Db::startTrans();
        try {
            //是不是已经领完了
            $isDone = FanclubBox::where('id',$box_id)->where('open_people < people')->update([
                'open_people' => Db::raw('open_people+1'),
            ]);
            if(!$isDone) return;
            
            //确定其他人没有比我先抢到
            $isDone = Db::name('fanclub_box_user')->where('id', $boxUser['id'])->where('delete_time IS NOT NULL')->update([
                'delete_time' => NULL,
                'user_id' => $uid,
            ]);
            if(!$isDone) return;

            //更新我的货币，且写入日志
            (new UserService)->change($uid, ['coin' => $boxUser['count']], '粉丝团宝箱奖励');

            //完成师徒任务
            RecTaskfather::addRec($uid, [8, 19, 30, 41]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
