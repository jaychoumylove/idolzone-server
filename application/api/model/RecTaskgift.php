<?php
namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class RecTaskgift extends Base
{

    /**
     * 进度
     */
    public static function getProgress($uid, $cid)
    {
        return self::where([
            'user_id' => $uid,
            'cid' => $cid
        ])->column('task_id');
    }

    /**
     * 领奖励
     */
    public static function settleHandle($cid, $task_id, $awardsList, $uid)
    {
        Db::startTrans();
        try {
            self::create([
                'user_id' => $uid,
                'task_id' => $task_id,
                'cid' => $cid
            ]);
            (new User())->change($uid, json_decode($awardsList[$task_id]['awards'],true), $awardsList[$task_id]['title'] . '奖励');
            
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
