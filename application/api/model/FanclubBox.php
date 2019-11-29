<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Model;

class FanclubBox extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**宝箱列表 */
    public static function getBoxList($fid, $uid)
    {
        $res['list'] = self::with('user')->where('fanclub_id', $fid)->whereTime('create_time', '>', time() - 3600 * 24)->order('id desc')->select();
        $res['can_settle'] = 0;
        foreach ($res['list'] as &$value) {
            // 已领取的
            if (
                FanclubBoxUser::where('box_id', $value['id'])->where('user_id', $uid)->value('id')
                || FanclubBoxUser::where('box_id', $value['id'])->count('id') >= $value['people']
            ) {
                $value['settle'] = true;
            } else {
                $res['can_settle']++;
            }
        }

        return $res;
    }

    /**发宝箱 */
    public static function sendbox($uid, $type, $consume, $people)
    {
        Db::startTrans();
        try {
            // 扣钻石、鲜花
            if ($type == 0) {
                // 钻石 // 1 = 3000
                $coin = $consume * 3000;
                (new User)->change($uid, ['stone' => -$consume], '发宝箱');
            } else if ($type == 1) {
                // 积分 // 1 = 300
                $coin = $consume * 300;
                $myScore = Db::name('pk_user_rank')->where('uid', $uid)->order('id desc')->value('score');
                if ($myScore < $consume * 10000) Common::res(['code' => 1, 'msg' => '积分不足']);
                Db::name('pk_user_rank')->where('uid', $uid)->order('id desc')->limit(1)->update(['score' => Db::raw('score-' . $consume * 10000)]);
            } else if ($type == 2) {
                // 鲜花 // 1 = 1
                $coin = $consume * 1;
                (new User)->change($uid, ['flower' => -$consume], '发宝箱');
            }

            $fid = FanclubUser::where('user_id', $uid)->value('fanclub_id');
            self::create(['user_id' => $uid, 'fanclub_id' => $fid, 'coin' => $coin, 'people' => $people]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }
}
