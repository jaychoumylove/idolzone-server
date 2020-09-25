<?php


namespace app\api\model;


use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Request;
use think\Response;

class ActiveYingyuan extends Base
{
    const SUP = 'sup'; // 打卡
    const EXT = 'ext'; // 拉新助力

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')
            ->bind ('nickname,avatarurl');
    }

    protected function setSupTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * @param string $type
     * @return string|true
     */
    public static function checkYingyuan($type = self::SUP)
    {
        $info = Cfg::getCfg (Cfg::ACTIVE_YINGYUAN);
        $date = date ('Y-m-d');

        if ($date < $info['date'][0]) {
            return '活动未开始';
        }

        if ($date > $info['date'][1]) {
            return '活动已结束';
        }

        $request = Request::instance ();
        $platform = $request->param ('platform');
        if (in_array ($platform, $info['platform']) == false) {
            return '平台不支持';
        }

        if ($type == self::EXT) {
            if ($date < $info['ext_time']) {
                return '补签暂未开始';
            }
        }

        return true;
    }

    /**
     * 应援打卡
     * @param        $starId
     * @param        $uid
     * @param string $type
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function setCard($starId, $uid, $type = self::SUP)
    {
        $typeMap = [self::SUP, self::EXT];
        if (in_array ($type, $typeMap) == false) Common::res (['msg' => "打卡错误", 'code' => 1]);

        $exist = self::where ('star_id', $starId)
            ->where ('user_id', $uid)
            ->order ('id', 'asc')
            ->find ();

        if ($exist) {
            if ($type == self::SUP) {
                $time = $exist['sup_time'];
                $day = explode (' ', $time)[0];
                if ($day == date ('Y-m-d')) {
                    Common::res (['msg' => "今日打卡次数已用完", 'code' => 1]);
                }
            }

            $update = [
                'sup_num' => bcadd ($exist['sup_num'], 1)
            ];

            if ($type == self::SUP) {
                $update['sup_time'] = date ('Y-m-d H:i:s');
            }
            if ($type == self::EXT) {
                $update['sup_ext'] = bcadd ($exist['sup_ext'], 1);
            }

            self::where(['id' => $exist['id']])->update ($update);
        } else {
            self::create ([
                'user_id' => $uid,
                'star_id' => $starId,
                'sup_num' => $type == self::SUP ? 1: 0,
                'sup_ext' => $type == self::SUP ? 0: 1,
            ]);
        }

        // 打卡赢能量
        if ($type == self::SUP) {
            (new UserService())->change ($uid, ['coin' => 1000], ['type' => Rec::YINGYUAN]);
        }
    }

    /**
     * 应援详情
     * @param $starId
     * @param $uid
     * @return array
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getYingyuan($starId, $uid)
    {
        $info = Cfg::getCfg (Cfg::ACTIVE_YINGYUAN);

        $infoProgress = $info['progress'];
        $infoProgressReward = $info['progress_reward'];

        $progressing = [
            'done' => 0, // 当前进程
            'doing' => $infoProgress[1]['step'],// 下一步进程
        ];

        $progressingReward = [
            'done' => 0, // 当前进程
            'doing' => $infoProgressReward[1]['step'],// 下一步进程
        ];

        $people = [
            'join_num' => 0,
            'finish_num' => 0,
        ];

        $is_today = false;

        // 拿到排名数据
        $list = self::where ('star_id', $starId)
            ->where('sup_num', '>', 0)
            ->order ('sup_num', 'desc')
            ->select ();

        // 参与人数
        $joinNum = self::where ('star_id', $starId)
            ->where('sup_num', '>', 0)
            ->count ();

        if ($joinNum) $people['join_num'] = $joinNum;

        $finishNum = self::where ('star_id', $starId)
            ->where('sup_num', '>=', 7)
            ->count ();

        if ($finishNum){
            $people['finish_num'] = $finishNum;
        }

        if ($list) {

            // 获取当前阶段的奖励和进程
            $curSteps = array_filter ($infoProgress, function ($item) use ($people) {
                return $item['step'] <= $people['finish_num'];
            });
            if($curSteps){
                $endSteps = $curSteps[bcsub (count ($curSteps), 1)];
                $progressing ['done'] = $endSteps['step'];
            }

            // 获取下一阶段的奖励和进程
            $nextSteps = array_filter ($infoProgress, function ($item) use ($people) {
                return $item['step'] > $people['finish_num'];
            });

            if (empty($nextSteps)) {
                // 已解锁最高奖励
                $end = $infoProgress[bcsub (count ($infoProgress), 1)];
                $progressing['doing'] = $end['step'];
            } else {
                $nextSteps = array_values ($nextSteps);

                $progressing['doing'] = $nextSteps[0]['step'];
            }

        }
        if (empty($self)) {
            // 用户打卡贡献不再前30名内
            $self = self::where ('star_id', $starId)
                ->where ('user_id', $uid)
                ->where ('sup_num', '>', 0)
                ->find ();

            if (empty($self)) {
                // 用户没有贡献打卡
                $self = [
                    'sup_num' => 0,
                    'sup_ext' => 0
                ];
            } else {
                $is_today = date ('Y-m-d') == explode (' ', $self['sup_time'])[0];
            }
        }

        $userExtInfo = (new UserExt)->readMaster()->where(['user_id' => $uid])->field('yingyuan_reward,yingyuan_reward_get_num')->find();
        //奖励阶段
        $my_sup_num = $self['sup_num'];
        if($userExtInfo['yingyuan_reward_get_num']-0>0){
            $my_sup_num = $my_sup_num - $userExtInfo['yingyuan_reward_get_num'];
        }
        if($my_sup_num>0){

            // 获取当前阶段的奖励和进程
            $curStepsReward = array_filter ($infoProgressReward, function ($item) use ($my_sup_num) {
                return $item['step'] <= $my_sup_num;
            });
            if($curStepsReward){
                $endStepsReward = $curStepsReward[bcsub (count ($curStepsReward), 1)];
                $progressingReward ['done'] = $endStepsReward['step'];
            }

            // 获取下一阶段的奖励和进程
            $nextStepsReward = array_filter ($infoProgressReward, function ($item) use ($my_sup_num) {
                return $item['step'] > $my_sup_num;
            });

            if (empty($nextStepsReward)) {
                // 已解锁最高奖励
                $endReward = $infoProgressReward[bcsub (count ($infoProgressReward), 1)];
                $progressingReward['doing'] = $endReward['step'];
            } else {
                $nextStepsReward = array_values ($nextStepsReward);

                $progressingReward['doing'] = $nextStepsReward[0]['step'];
            }
        }

        //奖金进度
        $step = array_filter ($infoProgress, function($item) {
            return $item['step'] > 0;
        });

        $step = array_map(function ($item) use ($people, $progressing) {

            if ($item['step'] < $progressing['doing']) {
                $item['precent'] = 100;
            }
            if ($item['step'] == $progressing['doing']) {
                $item['precent'] = bcdiv ($people['finish_num'], $item['step'], 2) * 100;
            }
            if ($item['step'] > $progressing['doing']) {
                $item['precent'] = 0;
            }

            return $item;
        }, $step);

        array_values ($step);

        //奖励进度
        $yingyuan_reward = $userExtInfo['yingyuan_reward'];
        $yingyuan_reward = json_decode($yingyuan_reward,true);

        $steps_reward = array_filter ($infoProgressReward, function($item) {
            return $item['step'] > 0;
        });
        $steps_reward = array_map(function ($item) use ($progressingReward,$my_sup_num,$yingyuan_reward) {
            $item['is_get'] = 0;
            if(in_array($item['index'],$yingyuan_reward)){
                $item['is_get'] = 1;
            }

            if ($item['step'] < $progressingReward['doing']) {
                $item['precent'] = 100;
            }
            if ($item['step'] == $progressingReward['doing']) {
                if($progressingReward['doing']!=$progressingReward['done']){
                    $item['precent'] = bcdiv (($my_sup_num-$progressingReward['done']), $item['step']-$progressingReward['done'], 2) * 100;
                }else{
                    $item['precent'] = 100;
                }
            }
            if ($item['step'] > $progressingReward['doing']) {
                $item['precent'] = 0;
            }

            return $item;
        }, $steps_reward);

        array_values ($steps_reward);

        $sup_ext = date('Y-m-d') < $info['ext_time'] ? false: true;

        return compact ('self','progressing','progressingReward', 'info', 'people', 'step', 'is_today','steps_reward', 'sup_ext');
    }
}