<?php


namespace app\api\controller\v1;


use app\api\model\AnimalLottery;
use app\api\model\Cfg;
use app\api\model\CfgAnimal;
use app\api\model\CfgAnimalLevel;
use app\api\model\CfgManorBackground;
use app\api\model\CfgPanaceaTask;
use app\api\model\CfgUserLevel;
use app\api\model\CfgWealActivityTask;
use app\api\model\ManorStealLog;
use app\api\model\RecPanaceaTask;
use app\api\model\RecUserBackgroundTask;
use app\api\model\RecWealActivityTask;
use app\api\model\UserAnimal;
use app\api\model\UserAnimalBox;
use app\api\model\UserExt;
use app\api\model\UserManor;
use app\api\model\UserManorBackground;
use app\api\model\UserManorFriendApply;
use app\api\model\UserManorFriends;
use app\api\model\UserManorLog;
use app\api\model\UserStar;
use app\base\controller\Base;
use app\base\service\Common;

class Animal extends Base
{
    public function getAnimalList()
    {
        $this->getUser();
        $type = input('type');

        if (in_array($type, ['already', 'all', 'yet', 'twelve','secret']) == false) {
            Common::res(['code' => 1, 'msg' => '请选择查看类型']);
        }

        $scrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');
        $supportItem = 3;
        if ($type == 'twelve') {
            $list = CfgAnimal::where('type', 'NORMAL')->order('create_time', 'desc')->select();

            if (is_object($list)) $list = $list->toArray();

            $animalIds = array_column($list, 'id');

            $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id', '*', ['user_id' => $this->uid]);
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $lv                   = 1;
                if (array_key_exists($value['id'], $userAnimalDict)) {
                    $value['user_animal'] = $userAnimalDict[$value['id']];
                    $lv                   = $value['user_animal']['level'];
                    $nextLv = CfgAnimalLevel::where('animal_id', $value['id'])
                        ->where('level', bcadd($lv, 1))
                        ->find();

                    if (empty($nextLv)) {
                        $value['up_able'] = false;
                    } else {
                        $value['up_able'] = $value['user_animal']['scrap'] >= $nextLv['number'];
                    }
                }

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $lv)
                    ->find();

                $list[$key] = $value;
            }
            array_multisort($list, array_column($list, 'user_animal'));
        }

        if ($type == 'secret') {
            $list = CfgAnimal::where('type', 'in', [CfgAnimal::SECRET, CfgAnimal::STAR_SECRET])
                ->order('create_time', 'desc')
                ->select();

            if (is_object($list)) $list = $list->toArray();

            $animalIds = array_column($list, 'id');

            $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id', '*', ['user_id' => $this->uid]);
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $lv                   = 1;
                if (array_key_exists($value['id'], $userAnimalDict)) {
                    $value['user_animal'] = $userAnimalDict[$value['id']];
                    $lv                   = $value['user_animal']['level'];

                    $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                        ->where('level', $lv)
                        ->find();
                    $nextLv = CfgAnimalLevel::where('animal_id', $value['id'])
                        ->where('level', bcadd($lv, 1))
                        ->find();

                    if ($value['type'] == CfgAnimal::STAR_SECRET) {
                        if ($value['image'] == $userAnimalDict[$value['id']]['use_image']) {
                            // 猫
                        } else {
                            // 爱豆萌宠
                            $value['image'] = $userAnimalDict[$value['id']]['use_image'];
                            $starId = UserStar::getStarId($this->uid);
                            $starAnimal = CfgAnimal::where('star_id', $starId)
                                ->where('type', CfgAnimal::STAR)
                                ->find();
                            $value['name'] = $starAnimal['name'];
                        }
                    }

                    if (empty($nextLv)) {
                        $value['up_able'] = false;
                        $value['need_scrap'] = $value['lv_info']['number'];
                    } else {
                        $value['up_able'] = $scrapNum >= $nextLv['number'];
                        $value['need_scrap'] = $nextLv['number'];
                    }
                } else {
                    $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                        ->where('level', $lv)
                        ->find();
                    $value['need_scrap'] = $value['lv_info']['number'];
                }
                $value['scrap_num'] = $scrapNum;

                if (empty($value['user_animal'])) {
                    $value['exchanged'] = $scrapNum >= $value['lv_info']['number'];
                }

                $list[$key] = $value;
            }
            array_multisort($list, array_column($list, 'user_animal'));
            $supportItem = 2;
        }

        $currentTime = time();
        $manor = UserManor::get(['user_id' => $this->uid]);
        $diffTime = bcsub($currentTime, $manor['last_output_time']);
        $output = (int)UserAnimal::getOutput($this->uid, CfgAnimal::OUTPUT);
        if ((int)$output != (int) $manor['output']) {
            UserManor::where('id', $manor['id'])
                ->where('output', $manor['output'])
                ->update(['output' => $output]);
        }
        $addCount = (int)UserAnimal::getOutputNumber($this->uid, $diffTime, $manor['count_left']);
        $mainAnimalId = (int)$manor['use_animal'];
        $stealLeft = UserAnimal::getStealLeft($this->uid);

        $nums = UserExt::where('user_id', $this->uid)->value('animal_lottery');
        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        $maxLottery = (int)$config['lottery']['max'];
        if ($nums > $maxLottery) {
            $lotteryLeft = 0;
        } else {
            $lotteryLeft = (int)bcsub($maxLottery, $nums);
        }

        $animalIds = CfgAnimal::where('type', 'NORMAL')->column('id');

        $normalAnimalNum = UserAnimal::where('user_id', $this->uid)
            ->where('animal_id', 'in', $animalIds)
            ->count();
        $callType = $normalAnimalNum == 12 ? 'goSupple': 'goCall';

        Common::res(['data' => [
            'list' => $list,
            'output' => $output,
            'add_count' => $addCount,
            'list_support' => $supportItem,
            'steal_left' => $stealLeft,
            'main_animal' => $mainAnimalId,
            'scrap_num' => $scrapNum,
            'lottery_left' => $lotteryLeft,
            'max_lottery'  => $maxLottery,
            'call_type' => $callType,
        ]]);
    }

    public function getAnimalLotteryInfo()
    {
        $this->getUser();
        // 获取宠物奖池信息
        $list = AnimalLottery::with(['animal'])
            ->order([
                'chance' => 'desc',
                'id' => 'asc'
            ])
            ->select();

        if (is_object($list)) $list = $list->toArray();

        $ids = array_column($list, 'animal');
        $scrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');
        $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $ids, 'animal_id', '*', ['user_id' => $this->uid]);
        foreach ($list as $key => $value) {
            $value['scrap_num'] = 0;
            if (array_key_exists($value['animal'], $userAnimalDict)) {
                $value['scrap_num'] = $userAnimalDict[$value['animal']]['scrap'];
            }
            if ($value['animal']['type'] == 'SECRET') {
                $value['scrap_num'] = $scrapNum;
            }

            $list[$key] = $value;
        }

        Common::res(['data' => $list]);
    }

    public function upAnimal()
    {
        // 宠物升级
        $this->getUser();
        $animalId = (int)input('animal_id', 0);
        if (empty($animalId)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        UserAnimal::lvUp($this->uid, $animalId);

        UserManor::refreshOutput($this->uid);

        Common::res();
    }

    public function unLockAnimal()
    {
        // 解锁宠物
        $this->getUser();
        $animalId = (int)input('animal_id', 0);
        if (empty($animalId)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        UserAnimal::unlock($this->uid, $animalId);

        UserManor::refreshOutput($this->uid);

        Common::res();
    }

    public function lotteryAnimal()
    {
        // 抽奖
        $this->getUser();
        $type = input('type', 'once');
        $data = AnimalLottery::lottery($type, $this->uid);

        if ((int)$data['has_new']) {
            UserManor::refreshOutput($this->uid);
        }

        Common::res(compact('data'));
    }

    public function getAnimalInfo()
    {
        $this->getUser();
        // 获取宠物信息
        $animalId = (int)input('animal_id', 0);
        if (empty($animalId)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        $animal = CfgAnimal::get($animalId);
        if (empty($animal)) {
            Common::res(['code' => 1, 'msg' => '宠物暂未开放']);
        }

        $userAnimal = UserAnimal::get(['animal_id' => $animalId, 'user_id' => $this->uid]);
        if (empty($userAnimal)) {
            Common::res(['code' =>1, 'msg' => '尚未拥有哦']);
        }

        $lv = empty($userAnimal) ? 0: $userAnimal['level'];
        $nextLv = bcadd($lv, 1);

        $scrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');
        $lvDict = CfgAnimalLevel::getDictList((new CfgAnimalLevel()), [$lv, $nextLv], 'level', '*', ['animal_id' => $animalId]);

        $exchangeImage = null;
        if ($animal['type'] == CfgAnimal::STAR_SECRET) {
            if ($lv) {
                $animal['use_image'] = $userAnimal['use_image'];
                $starId = UserStar::getStarId($this->uid);
                $starAnimal = CfgAnimal::get(['type' => CfgAnimal::STAR, 'star_id' => $starId]);
                if ($starAnimal) {
                    $exchangeImage = $starAnimal;
                }

                if ($animal['image'] == $userAnimal['use_image']) {
                    // 猫
                    $animal['use_name'] = $animal['name'];
                } else {
                    // 爱豆萌宠
                    $animal['use_name'] = $starAnimal['name'];
                }
            }
        }

        $data = [
            'animal' => $animal,
            'lv' => $lvDict[$lv],
            'changeImage' => $exchangeImage,
            'next_lv' => array_key_exists($nextLv, $lvDict) ? $lvDict[$nextLv]: null,
            'scrap_num' => $animal['type'] == 'NORMAL' ? $userAnimal['scrap'] : $scrapNum,
        ];

        Common::res(compact('data'));
    }

    public function checkSecretImage()
    {
        $this->getUser();

        UserAnimal::checkoutSecretImage($this->uid);
        Common::res();
    }

    public function animalSteal()
    {
        // 宠物偷豆
        $this->getUser();
        $steal_id = (int)input('user_id', 0);
        if (empty($steal_id)) {
            Common::res(['code' => 1, 'msg' => '请选择偷取用户']);
        }

        UserManor::steal($this->uid, $steal_id);
        Common::res();
    }

    public function stealLog()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);

        $list = ManorStealLog::with(['user'])
            ->where('steal_id', $this->uid)
            ->page($page, $size)
            ->select();
        if (is_object($list)) $list = $list->toArray();

        Common::res(['data' => $list]);
    }

    public function stealUserList()
    {
        $this->getUser();
        $list = UserManor::getRandomStealUser($this->uid);
        $daySteal = UserManor::where('user_id', $this->uid)->value('day_steal');
        $sumSteal = UserAnimal::getOutput($this->uid, CfgAnimal::STEAL);
        $diff = (int)bcsub($sumSteal, $daySteal);
        Common::res(['data' => [
            'list' => $list,
            'steal_left_times' => $diff > 0 ? $diff: 0
        ]]);
    }

    public function animalOutput()
    {
        // 宠物产豆
        $this->getUser();

        UserManor::animalOutput($this->uid);
        Common::res();
    }

    public function changeMainAnimal()
    {
        $animalId = (int)input('animal_id', 0);
        if (empty($animalId)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }
        $this->getUser();

        UserManor::animalChange($this->uid, $animalId);
        Common::res();
    }

    public function getCfgBackground()
    {
        // 获取庄园背景列表
        $this->getUser();
        $type = input('type', false);
        if (empty($type)) {
            Common::res(['code' => 1, 'msg' => '请选择类型']);
        }

        $map = [
            'type' => $type,
            'status' => CfgManorBackground::ON
        ];

        if ($type == 'star') {
            $map['star_id'] = UserStar::getStarId($this->uid);
        }

        $background = UserManorBackground::where('user_id', $this->uid)->column('background');
        $manor = UserManor::get(['user_id' => $this->uid]);
        $useBackground = $manor['background'];
        $tryData = array_column($manor['try_data'], 'time', 'id');
        $currentTime = time();

        $list = CfgManorBackground::where($map)
            ->order([
                'create_time' => 'desc',
                'id' => 'desc'
            ])
            ->select();

        $try = [];
        foreach ($manor['try_data'] as $item) {
            if ($item['time'] > $currentTime) {
                $try = $item;
            }
        }

        foreach ($list as $key => $value) {
            $value['locked'] = in_array($value['id'], $background);
            $value['used'] = $value['id'] == $useBackground;
            $value['try'] = 0;
            if (!$value['locked']) {
                if (array_key_exists($value['id'], $tryData)) {
                    $value['try'] = -1;
                    if ($try) {
                        if ($value['id'] == $try['id'] && $try['time'] > $currentTime) {
                            $value['try'] = 1;
                        }
                    }
                }
                if ($value['lock_data']) {
                    if ($value['lock_data']['type'] == 'currency') {
                        if (empty($backgroundNum)) {
                            $backgroundRec = new RecUserBackgroundTask();
                            $backgroundType = $type == 'active' ? RecUserBackgroundTask::ACTIVE: RecUserBackgroundTask::FLOWER_SUM;
                            $backgroundNum = $backgroundRec->where('user_id', $this->uid)->where('type', $backgroundType)->value('sum', 0);
                        }
                        $value['able_lock'] = $backgroundNum >= $value['lock_data']['number'];
                    }
                    if ($value['lock_data']['type'] == 'week_rank') {
                        if (empty($weekNum)) {
                            $weekNum = UserStar::where('user_id', $this->uid)->value('thisweek_count', 0);
                        }
                        $value['able_lock'] = $weekNum >= $value['lock_data']['number'];
                    }
                    if ($value['lock_data']['type'] == 'day_rank') {
                        if (empty($dayNum)) {
                            $dayNum = UserStar::where('user_id', $this->uid)->value('thisday_count', 0);
                        }
                        $value['able_lock'] = $dayNum >= $value['lock_data']['number'];
                    }
                    if ($value['lock_data']['type'] == 'level') {
                        if (empty($level)) {
                            $level = CfgUserLevel::getLevel($this->uid);
                        }
                        $value['able_lock'] = $level >= $value['lock_data']['number'];
                    }

                    if (array_key_exists('limit', $value['lock_data'])) {
                        $value['end_time'] = strtotime($value['lock_data']['limit']['end']);
                    }
                }
            }
            $list[$key] = $value;
        }

        Common::res(['data' => $list]);
    }

    public function tryBackground()
    {
        // 试用庄园背景
        $this->getUser();
        $background = (int)input('background', 0);
        if (empty($background)) {
            Common::res(['code' => 1, 'msg' => '请选择试用背景哦']);
        }

        $backgroundInfo = CfgManorBackground::get($background);
        if (empty($backgroundInfo)) {
            Common::res(['code' => 1, 'msg' => '暂未开放']);
        }

        if ($backgroundInfo['status'] == CfgManorBackground::OFF) {
            Common::res(['code' => 1, 'msg' => '已经下架了哦']);
        }

        $exist = UserManorBackground::get(['user_id' => $this->uid, 'background' => $background]);
        if ($exist) {
            Common::res(['code' => 1, 'msg' => '已经解锁了哦']);
        }

        $manor = UserManor::get(['user_id' => $this->uid]);
        $tryData = $manor['try_data'];
        if ($tryData) {
            $tryIds = array_column($tryData, 'id');
            if (in_array($background, $tryIds)) {
                Common::res(['code' => 1, 'msg' => '已经试用过了哦']);
            }
        }

        $currentTime = time();
        $config = Cfg::getCfg(Cfg::MANOR_ANIMAL);
        $minute = $config['background_try_minute'];
        $tryTime = bcmul($minute, 60);
        $data = ['id' => $background, 'time' => bcadd($currentTime, $tryTime)];
        array_push($tryData, $data);
        $tryData = json_encode($tryData);

        UserManor::where('user_id', $this->uid)->update(['try_data' => $tryData]);

        Common::res();
    }

    public function useBackground()
    {
        // 使用庄园背景
        $this->getUser();
        $background = (int)input('background', 0);
        if (empty($background)) {
            Common::res(['code' =>1, 'msg' => '请选择使用背景']);
        }

        $backgroundInfo = CfgManorBackground::get($background);
        if (empty($backgroundInfo)) {
            Common::res(['code' => 1, 'msg' => '背景不存在']);
        }

        if ($backgroundInfo['status'] == CfgManorBackground::OFF) {
            Common::res(['code' => 1, 'msg' => '背景已下架']);
        }

        $exist = UserManorBackground::get(['user_id' => $this->uid, 'background' => $background]);
        if (empty($exist)) {
            Common::res(['code' => 1, 'msg' => '您还未解锁该背景哦']);
        }
        $update = ['background' => $background];

        $manor = UserManor::get(['user_id' => $this->uid]);
        $tryData = $manor['try_data'];
        if ($tryData) {
            $currentTime = time();
            $newTryData = array_map(function ($item) use($currentTime) {
                if ($item['time'] > $currentTime) {
                    $item['time'] = $currentTime;
                }

                return $item;
            }, $tryData);

            $update['try_data'] = json_encode($newTryData);
        }

        UserManor::where('user_id', $this->uid)->update($update);

        Common::res();
    }

    public function unlockBackground()
    {
        $this->getUser();

        $background = (int)input('background', 0);
        if (empty($background)) {
            Common::res(['code' =>1, 'msg' => '请选择使用背景']);
        }

        $backgroundInfo = CfgManorBackground::get($background);
        if (empty($backgroundInfo)) {
            Common::res(['code' => 1, 'msg' => '背景不存在']);
        }

        if ($backgroundInfo['status'] == CfgManorBackground::OFF) {
            Common::res(['code' => 1, 'msg' => '背景已下架']);
        }

        $exist = UserManorBackground::get(['user_id' => $this->uid, 'background' => $background]);
        if ($exist) {
            Common::res(['code' => 1, 'msg' => '已解锁']);
        }

        UserManor::unlockBackground($this->uid, $background);

        Common::res();
    }

    public function getTaskList()
    {
        $this->getUser();
        // 任务列表
        $list = (new CfgPanaceaTask())->getList($this->uid);

        Common::res(['data' => $list]);
    }

    public function settleTask()
    {
        $this->getUser();
        $task_id = $this->req('task_id', 'integer');
        $task = (new CfgPanaceaTask())->get($task_id);
        if(!$task){
            Common::res(['code' => 1, 'msg' => '不存在该任务']);
        }
        if ($task['type'] != CfgPanaceaTask::ONCE) {
            $rectask= RecPanaceaTask::where(['user_id'=>$this->uid,'task_id'=>$task_id])->find();

            if (empty($rectask)) {
                Common::res (['code' => 1, 'msg' => "还未完成该任务哦"]);
            }
        }

        $earn = (new RecPanaceaTask())->settle($task_id, $this->uid);

        Common::res(['data' => $earn]);
    }

    public function appleFriend()
    {
        $this->getUser();
        $friend = (int)input('friend', 0);
        if (empty($friend)) {
            Common::res(['code' => 1, 'msg' => '请选择申请的好友']);
        }

        UserManorFriendApply::applyFriend($this->uid, $friend);

        Common::res();
    }

    public function addFriend()
    {
        $this->getUser();
        $friend = (int)input('friend', 0);
        if (empty($friend)) {
            Common::res(['code' => 1, 'msg' => '请选择申请的好友']);
        }

        UserManorFriends::addFriend($this->uid, $friend);

        Common::res();
    }

    public function removeFriend()
    {
        $this->getUser();
        $friend = (int)input('friend', 0);
        if (empty($friend)) {
            Common::res(['code' => 1, 'msg' => '请选择好友']);
        }

        UserManorFriends::removeFriend($this->uid, $friend);

        Common::res();
    }

    public function friendList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);

        $self = $this->uid;

        $friendList = UserManorFriends::with(['friend', 'user'])
            ->where('user_id|friend_id', $self)
            ->select();
        if (is_object($friendList)) $friendList = $friendList->toArray();

        $userIds = array_column($friendList, 'user_id');
        $friendIds = array_column($friendList, 'friend_id');
        $ids = array_merge($userIds, $friendIds);
        $ids = array_filter($ids, function ($ite) use ($self) {
            return $ite != $self;
        });
        $ids = array_unique($ids);

        $manorModel = new UserManor();
        $list = $manorModel::with(['user'])
            ->where('user_id', 'in', $ids)
            ->order(['day_lottery_times' => 'desc'])
            ->page($page, $size)
            ->select();

        $now  = time();
        $date = date('Y-m-d H:i:s', $now);

        foreach ($list as $key => $value) {
            unset($value['friend']);
            $value['friend'] = $value['user'];
            unset($value['user']);
            $friendId = $value['user_id'];

            $value['manor_output'] = $value['output'];

            $boxNum = UserAnimalBox::where('user_id', $friendId)
                ->where('end_time', '>', $date)
                ->where('lottery_user', 'null')
                ->where('lottery_time', 'null')
                ->count();

            $box = UserAnimalBox::where('user_id', $friendId)
                ->where('end_time', '>', $date)
                ->where('lottery_user', 'null')
                ->where('lottery_time', 'null')
                ->order(['id' => 'desc'])
                ->find();

            $value['box'] = [
                'scrap_name' => $box['scrap_name'],
                'scrap_img' => $box['scrap_img'],
                'number' => $boxNum,
            ];

            $list[$key] = $value;
        }

        Common::res(['data' => $list]);
    }
    
    public function manorLog()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 15);

        $logList = UserManorLog::where('user_id', $this->uid)
            ->order('id desc')
            ->page($page, $size)
            ->select();

        Common::res(['data' => $logList]);
    }

    public function boxAnimal()
    {
        $user_id = (int)input('user_id', 0);
        if (empty($user_id)) {
            $this->getUser();
            $user_id = $this->uid;
        }
        $now  = time();
        $date = date('Y-m-d H:i:s', $now);

        $list = UserAnimalBox::where('user_id', $user_id)
            ->where('end_time', '>', $date)
            ->where('lottery_user', 'null')
            ->where('lottery_time', 'null')
            ->order(['end_time' => 'asc'])
            ->select();

        if (is_object($list)) $list = $list->toArray();
        $newList = [];
        $numbers = range(1, 12);
        shuffle($numbers);
        foreach ($list as $key => $value) {
            if (array_key_exists($value['animal_id'], $newList)) {
                $newList[$value['animal_id']]['number'] = (int)bcadd(1, $newList[$value['animal_id']]['number']);
            } else {
                $newList[$value['animal_id']] = [
                    'animal_id' => $value['animal_id'],
                    'scrap_img' => $value['scrap_img'],
                    'scrap_name' => $value['scrap_name'],
                    'number' => 1,
                    'position' => array_pop($numbers)
                ];
            }
        }

        Common::res(['data' => $newList]);
    }

    public function boxAnimalLottery()
    {
        $this->getUser();
        $target_user = (int)input('user_id', 0);
        if (empty($target_user)) {
            Common::res(['code' => 1, 'msg' => '请选择庄园主']);
        }

        $data = UserAnimalBox::lottery($this->uid, $target_user);

        Common::res(['data' => $data]);
    }

    public function getActiveIdolSumRank()
    {
        // 国庆节庄园排行
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);

        $data = UserManor::getActiveIdolSumRank($page, $size);

        Common::res(['data' => $data]);
    }

    public function getActiveFansSumRank()
    {
        // 国庆节庄园排行
        $this->getUser();
        $star = (int)input('star_id', 0);
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $data = UserManor::getActiveFansSumRank($this->uid,$star, $page, $size);

        Common::res(['data' => $data]);
    }

    public function getActiveAllFansSumRank()
    {
        // 国庆节庄园排行
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $data = UserManor::getActiveAllFansSumRank($this->uid, $page, $size);

        Common::res(['data' => $data]);
    }
}