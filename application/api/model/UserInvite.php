<?php


namespace app\api\model;


use think\Db;
use think\Exception;

class UserInvite extends \app\base\model\Base
{
    public static function recordInvite($user_id, $star_id = 0)
    {
        if (empty($star_id)) $star_id = UserStar::getStarId ($user_id);

        $map  = compact ('user_id', 'star_id');

        $exist = (new self())->readMaster ()->where($map)->find ();
        if (empty($exist)) {
            $data = [
                'invite_day' => 1,
                'invite_sum' => 1,
            ];

            self::create (array_merge ($map, $data));
        } else {
            $data = [
                'invite_day' => bcadd ($exist['invite_day'], 1),
                'invite_sum' => bcadd ($exist['invite_sum'], 1),
            ];

            $updated = self::where('id', $exist['id'])->update($data);
            if (empty($updated)) return false;
        }

        return true;
    }

    public static function settleInvite($user_id, $star_id = 0, $settle = 0)
    {
        if (empty($star_id)) $star_id = UserStar::getStarId ($user_id);

        $config = Cfg::getCfg (Cfg::INVITE_ASSIST);
        $values = array_column ($config['my_progress'], 'value');

        if (in_array ($settle, $values) == false) {
            return '暂未开放';
        }

        $map  = compact ('user_id', 'star_id');

        $exist = (new self())->readMaster ()->where($map)->find ();
        if (empty($exist)) {
            return "你未达到领取条件";
        }

        if ((int)$exist['invite_day_settle'] >= $settle) {
            return "您已领取过了";
        }

        $reward = [];

        foreach ($config['my_progress'] as $item) {
            if ($item['value'] == $settle) {
                $reward = $item['reward'];
                break;
            }
            continue;
        }

        if (empty($reward)) {
            return "暂未开放";
        }

        Db::startTrans ();
        try {
            $updated = self::where('id', $exist['id'])->update(['invite_day_settle' => $settle]);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            $res = RecUserInvite::add ($user_id, $reward, 'user');
            if (empty($res)) {
                throw new Exception('新增领取记录失败');
            }


            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();

            return "请稍后再试";
        }

        return $reward;
    }
}