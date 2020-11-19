<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class CfgActiveOpen extends Base
{

    public function User()
    {
        return  $this->belongsTo('User', 'user_id', 'id')->field('id,avatarurl,nickname');
    }

    public function Star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    public static function getList()
    {
        $logList = self::with(['User', 'Star'])->select();

        return $logList;
    }

    public static function occupy($uid,$id)
    {
        $info = self::get($id);
        if (!$info) Common::res(['code' => 1, 'msg' => '该时间不存在']);
        $userStarInfo = (new UserStar)->readMaster()->where('user_id',$uid)->find();
        if (!$userStarInfo) Common::res(['code' => 1, 'msg' => '请先选择加入一个圈子']);
        if($userStarInfo['courage_occupy_times']==3){
            $need_courage = 30000;
        }elseif ($userStarInfo['courage_occupy_times']==2){
            $need_courage = 60000;
        }elseif ($userStarInfo['courage_occupy_times']==1){
            $need_courage = 100000;
        }elseif ($userStarInfo['courage_occupy_times']==0){
            $need_courage = 0;
        }

        Db::startTrans();
        try {
            if($need_courage>0){
                $isDone = UserStar::where('user_id',$uid)->where('courage_occupy_times',$userStarInfo['courage_occupy_times'])
                    ->where('courage','>=',$need_courage)->update(['courage_occupy_count' => Db::raw('courage')]);
                if (!$isDone) Common::res(['code' => 1, 'msg' => '您的勇气值不足']);
            }
            $isDone1 = StarRank::where('star_id',$userStarInfo['star_id'])->where('courage_occupy_times','>',0)->update([
                'courage_occupy_times' => Db::raw('courage_occupy_times-1'),
            ]);
            if (!$isDone1) Common::res(['code' => 1, 'msg' => '爱豆已占领三天']);
            $isDone2 = UserStar::where('user_id',$uid)->where('courage_occupy_times','>',0)->update([
                'courage_occupy_times' => Db::raw('courage_occupy_times-1'),
            ]);
            if (!$isDone2) Common::res(['code' => 1, 'msg' => '您已占领三天']);
            $isDone3 = self::where('id',$id)->where('user_id',null)->where('star_id',null)->update([
                'user_id' => $uid,
                'star_id' => $userStarInfo['star_id'],
            ]);
            if (!$isDone3) Common::res(['code' => 1, 'msg' => '已被占领，请刷新']);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

    }

}
