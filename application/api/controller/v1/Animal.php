<?php


namespace app\api\controller\v1;


use app\api\model\AnimalLottery;
use app\api\model\Cfg;
use app\api\model\CfgAnimal;
use app\api\model\CfgAnimalLevel;
use app\api\model\CfgPanaceaTask;
use app\api\model\CfgWealActivityTask;
use app\api\model\ManorStealLog;
use app\api\model\RecPanaceaTask;
use app\api\model\RecWealActivityTask;
use app\api\model\UserAnimal;
use app\api\model\UserExt;
use app\api\model\UserManor;
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

        // 获取宠物列表
        if ($type == 'already') {
            // 已经拥有
            $list = UserAnimal::with(['animal'])
                ->where('user_id', $this->uid)
                ->where('lock', 0)
                ->select();
            if (is_object($list)) $list = $list->toArray();

            foreach ($list as $key => $value) {

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['animal_id'])
                    ->where('level', $value['level'])
                    ->find();

                $list[$key] = $value;
            }
        }

        if ($type == 'all') {
            // 所有
            $list = CfgAnimal::all();
            if (is_object($list)) $list = $list->toArray();

            $animalIds = array_column($list, 'id');

            $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id');
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $lv = 1;
                if (array_key_exists($value['id'], $userAnimalDict)) {
                    $value['user_animal'] = $userAnimalDict[$value['id']];
                    $lv = $value['user_animal']['level'];
                }

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $lv)
                    ->find();

                $list[$key] = $value;
            }
        }

//        if ($type == 'yet') {
//            // 尚未拥有
//            $animalIds = UserAnimal::where('user_id', $this->uid)
//                ->where('lock', 0)
//                ->column('animal_id');
//
//            $list = CfgAnimal::where('id', 'not in', $animalIds)->select();
//            if (is_object($list)) $list = $list->toArray();
//            foreach ($list as $key => $value) {
//                // 补充数据
//                $value['user_animal'] = null;
//                $value['level'] = 1;
//
//                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
//                    ->where('level', $value['level'])
//                    ->find();
//
//                $list[$key] = $value;
//            }
//        }

        if ($type == 'yet') {
            $star = UserStar::getStarId($this->uid);// 只能看到自己家的
            $list = CfgAnimal::where('type', 'SECRET')
                ->where('star_id', $star)
                ->order('create_time', 'desc')
                ->select();

            if (is_object($list)) $list = $list->toArray();

            $animalIds = array_column($list, 'id');

            $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id', '*', ['user_id' => $this->uid]);
            $scrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $lv                   = 1;
                if (array_key_exists($value['id'], $userAnimalDict)) {
                    $value['user_animal'] = $userAnimalDict[$value['id']];
                    $value['empty_img'] = $value['image'];
                    $lv                   = $value['user_animal']['level'];
                }

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $lv)
                    ->find();

                if (empty($value['user_animal'])) {
                    $value['exchanged'] = $scrapNum >= $value['lv_info']['number'];
                }

                $list[$key] = $value;
            }
        }

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
                }

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $lv)
                    ->find();

                $list[$key] = $value;
            }
        }

        if ($type == 'secret') {
            $star = UserStar::getStarId($this->uid);// 只能看到自己家的
            $list = CfgAnimal::where('type', 'SECRET')
                ->where('star_id', $star)
                ->order('create_time', 'desc')
                ->select();

            if (is_object($list)) $list = $list->toArray();

            $animalIds = array_column($list, 'id');

            $userAnimalDict = UserAnimal::getDictList((new UserAnimal()), $animalIds, 'animal_id', '*', ['user_id' => $this->uid]);
            $scrapNum = UserExt::where('user_id', $this->uid)->value ('scrap');
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $lv                   = 1;
                if (array_key_exists($value['id'], $userAnimalDict)) {
                    $value['user_animal'] = $userAnimalDict[$value['id']];
                    $lv                   = $value['user_animal']['level'];
                }

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $lv)
                    ->find();

                if (empty($value['user_animal'])) {
                    $value['exchanged'] = $scrapNum >= $value['lv_info']['number'];
                }

                $list[$key] = $value;
            }
        }

        $currentTime = time();
        $manor = UserManor::get(['user_id' => $this->uid]);
        $diffTime = bcsub($currentTime, $manor['last_output_time']);
        $output = (int)UserAnimal::getOutput($this->uid, CfgAnimal::OUTPUT);
        $addCount = (int)UserAnimal::getOutputNumber($this->uid, $diffTime, $manor['count_left']);
        $mainAnimalId = (int)$manor['use_animal'];
        $stealLeft = UserAnimal::getStealLeft($this->uid);

        Common::res(['data' => [
            'list' => $list,
            'output' => $output,
            'add_count' => $addCount,
            'steal_left' => $stealLeft,
            'main_animal' => $mainAnimalId
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

        Common::res();
    }

    public function lotteryAnimal()
    {
        // 抽奖
        $this->getUser();
        $type = input('type', 'once');
        $data = AnimalLottery::lottery($type, $this->uid);

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

        $data = [
            'animal' => $animal,
            'lv' => $lvDict[$lv],
            'next_lv' => array_key_exists($nextLv, $lvDict) ? $lvDict[$nextLv]: null,
            'scrap_num' => $animal['type'] == 'NORMAL' ? $userAnimal['scrap'] : $scrapNum,
        ];

        Common::res(compact('data'));
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
    }

    public function useBackground()
    {
        // 使用庄园背景
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
}