<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class Father extends Base
{
    public function father()
    {
        return $this->belongsTo('User', 'father_uid', 'id')->field('id,nickname,avatarurl');
    }

    public function son()
    {
        return $this->belongsTo('User', 'son_uid', 'id')->field('id,nickname,avatarurl');
    }

    public static function type($uid)
    {
        $res['user'] = User::where('id', $uid)->field('id,nickname,avatarurl,type')->find();
        // 粉丝等级
        $res['user_level'] = CfgUserLevel::getLevel($uid);
        // 累计收益
        $res['earn'] = FatherEarn::where('user_id', $uid)->find();

        $farmCoin = UserSprite::where('user_id', $uid)->value('total_speed_coin');
        
        $hasFather = self::where('son_uid', $uid)->where('status', 1)->count();
        if (!$hasFather && $farmCoin >= 432) {
            // --师父--
            $res['type'] = 1;
            // 徒弟数量
            $res['sonCount'] = self::where('father_uid', $uid)->where('status', 1)->count();
            $res['maxSonCount'] = 10;
            $res['father_info'] = UserExt::where('user_id', $uid)->field('father_open_msg,father_open_img,father_notice')->find();
        } else {
            // --徒弟--
            $res['type'] = 2;
            // 师父
            $res['father'] = self::with('father')->where('son_uid', $uid)->where('status', 1)->find();
            // 可出师
            $res['can_exit'] = strtotime($res['father']['create_time']) + 3600 * 24 * 30  < time();
            $res['father_info'] = UserExt::where('user_id', $res['father']['father']['id'])->field('father_open_msg,father_open_img,father_notice')->find();
        }

        return $res;
    }

    /**师父列表 */
    public static function getFatherList($uid)
    {
        $star_id = UserStar::getStarId($uid);

        $data = Db::query("SELECT us.id,us.avatarurl,us.nickname FROM f_user_sprite sp 
                            join f_user_star st on st.user_id = sp.user_id
                            join f_user us on us.id = sp.user_id
                            where star_id = {$star_id}
                            and sp.total_speed_coin >= 432
                            ORDER BY RAND() limit 20;");

        foreach ($data as $key => &$value) {
            // 招徒宣言
            $value['father_open_msg'] = UserExt::where('user_id', $value['id'])->value('father_open_msg');
            // 粉丝等级
            $value['user_level'] = CfgUserLevel::getLevel($value['id']);

            $value['apply_status'] = self::where('father_uid', $value['id'])->where('son_uid', $uid)->value('status');
            $count = self::where('father_uid', $value['id'])->where('status', 1)->count();
            if ($count >= 10) unset($data[$key]);
        }

        return $data;
    }

    public static function baishi($father_uid, $son_uid ,$status=0)
    {
        if ($father_uid == $son_uid) {
            Common::res(['code' => 1, 'msg' => '师徒不能为同一人']);
        }
        if (UserStar::getStarId($father_uid) != UserStar::getStarId($son_uid)) {
            Common::res(['code' => 1, 'msg' => '师徒须属于同一个圈子']);
        }        
        $isExist = self::where('son_uid', $son_uid)->where('status', 1)->count();
        if ($isExist) {
            Common::res(['code' => 1, 'msg' => '你已经有师父了']);
        }
        $fatherCount = self::where('father_uid', $father_uid)->where('status', 1)->count();
        if ($fatherCount >= 10) {
            Common::res(['code' => 1, 'msg' => 'TA的徒弟已满']);
        }
        $applyCount = self::where('son_uid', $son_uid)->where('status','<', 1)->count();
        if ($applyCount >= 10) {
            Common::res(['code' => 1, 'msg' => '最多申请10个，等待师傅审核']);
        }
        $isExist = RecTaskfather::where('user_id', $son_uid)->where('is_settle', 1)->count();
        if ($isExist) {
            Common::res(['code' => 1, 'msg' => '不能再次拜师']);
        }
        
        $isDone = self::where(['father_uid' => $father_uid, 'son_uid' => $son_uid, 'status' => -1])->update(['status' => $status]);
        if(!$isDone){
            try {
                self::create(['father_uid' => $father_uid, 'son_uid' => $son_uid, 'status' => $status]);
            } catch (\Exception $e) {
                
                Common::res(['code' => 1,'msg' => '不能重复操作']);
            }
        }
    }

    /**徒弟列表 */
    public static function getSonList($active, $father_uid)
    {
        $taskIds = CfgTaskfather::where('father_level', $active)->column('id');
        $sonList = self::with(['son'])->where('father_uid', $father_uid)->where('status', 1)->order('id desc')->select();

        foreach ($sonList as &$son) {
            $complete = RecTaskfather::where('task_id', 'in', $taskIds)->where('user_id', $son['son_uid'])->where('is_settle', 1)->count();
            $son['complete'] = $complete . '/' . count($taskIds);

            $son['user_level'] = CfgUserLevel::getLevel($son['son_uid']);

            // 可出师
            if (strtotime($son['create_time']) + 3600 * 24 * 30  < time()) {
                $son['can_exit'] = true;
            }
        }

        return $sonList;
    }
    
    /**徒弟列表 */
    public static function getApplyList($father_uid)
    {
        $sonList = self::with(['son'])->where('father_uid', $father_uid)->where('status', 0)->order('id asc')->select();
    
        foreach ($sonList as &$son) {
            $son['user_level'] = CfgUserLevel::getLevel($son['son_uid']);
        }
    
        return $sonList;
    }
    
    /**处理申请列表 */
    public static function applyDeal($f_id, $uid, $status)
    {
        Db::startTrans();
        try {
            $isDone = self::where(['father_uid' => $f_id,'son_uid' => $uid])->where('status','<',1)->update(['status' => $status]);
            if($isDone && $status==1) self::where(['son_uid' => $uid])->where('status','<',1)->delete(true);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public static function exit($son_uid)
    {
        self::where('son_uid', $son_uid)->delete(true);
        FatherEarn::where('user_id', $son_uid)->destroy();
    }
}
