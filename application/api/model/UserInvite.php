<?php


namespace app\api\model;


use think\Db;
use think\Exception;

class UserInvite extends \app\base\model\Base
{
    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function getInviteDaySettleAttr($value)
    {
        return json_decode ($value, true);
    }

    public function setInviteDaySettleAttr($value)
    {
        if (is_array ($value)) {
            $value = json_encode ($value);
        }
        return $value;
    }

    public static function cleanDayInvite()
    {
        self::where('1=1')->update([
            'invite_day' => 0,
            'invite_day_settle' => json_encode ([]),
        ]);
    }

    public static function recordInvite($user_id, $star_id = 0)
    {
        if (empty($star_id)) $star_id = UserStar::getStarId ($user_id);

        $map  = [
            'user_id' => $user_id,
            'star_id' => $star_id
        ];

        $exist = (new self())->readMaster ()->where($map)->find ();
        if (empty($exist)) {
            $data = [
                'invite_day' => 1,
                'invite_sum' => 1,
                'invite_day_settle' => [],
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

    public static function settleInvite($user_id, $settle = 0, $star_id = 0)
    {
        $status = Cfg::checkInviteAssistTime ();
        if (empty($status)) {
            return "活动已截止";
        }

        if (empty($star_id)) $star_id = UserStar::getStarId ($user_id);

        $config = Cfg::getCfg (Cfg::INVITE_ASSIST);
        $values = array_column ($config['my_progress'], 'value');

        if (in_array ($settle, $values) == false) {
            return '暂未开放';
        }

        $map  = compact ('user_id', 'star_id');

        $model = (new self());

        $exist = $model->readMaster ()->where($map)->find ();
        if (empty($exist)) {
            return "你未达到领取条件";
        }

        if (in_array ($settle, $exist['invite_day_settle'])) {
            return "您已领取过了";
        }

        if ((int)$exist['invite_day'] < $settle) {
            return "您的邀请人数不够哦";
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
            $daySettle = $exist['invite_day_settle'];
            array_push ($daySettle, (int)$settle);
            $updated = $model->where('id', $exist['id'])->update(['invite_day_settle' => json_encode ($daySettle)]);
            if (empty($updated)) {
                throw new Exception('更新失败');
            }

            if ($reward['type'] == 'prop') {
                $endTimestamp = bcadd (time (), $reward['end_time']);
                $endTime = date('Y-m-d H:i:s', $endTimestamp);
                UserProp::addPropWithEnd ($user_id, $reward['key'], 1, $endTime);
            }
            if ($reward['type'] == 'currency') {
                (new \app\api\service\User())->change ($user_id, [$reward['key'] => $reward['number']], '拉新助力奖励');
            }

            $res = RecUserInvite::add ($user_id, $reward, 'user');
            if (empty($res)) {
                throw new Exception('新增领取记录失败');
            }

//            throw new Exception('something was wrong');

            Db::commit ();
        } catch (\Throwable $throwable) {
            Db::rollback ();
//            throw $throwable;
            return "请稍后再试";
        }

        return $reward;
    }
}