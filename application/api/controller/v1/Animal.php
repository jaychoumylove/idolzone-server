<?php


namespace app\api\controller\v1;


use app\api\model\AnimalLottery;
use app\api\model\CfgAnimal;
use app\api\model\CfgAnimalLevel;
use app\api\model\ManorStealLog;
use app\api\model\UserAnimal;
use app\api\model\UserManor;
use app\base\controller\Base;
use app\base\service\Common;

class Animal extends Base
{
    public function getAnimalList()
    {
        $this->getUser();
        $type = input('type');

        if (in_array($type, ['already', 'all', 'yet']) == false) {
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

        if ($type == 'yet') {
            // 尚未拥有
            $animalIds = UserAnimal::where('user_id', $this->uid)
                ->where('lock', 0)
                ->column('animal_id');

            $list = CfgAnimal::where('id', 'not in', $animalIds)->select();
            if (is_object($list)) $list = $list->toArray();
            foreach ($list as $key => $value) {
                // 补充数据
                $value['user_animal'] = null;
                $value['level'] = 1;

                $value['lv_info'] = CfgAnimalLevel::where('animal_id', $value['id'])
                    ->where('level', $value['level'])
                    ->find();

                $list[$key] = $value;
            }
        }

        $currentTime = time();
        $manor = UserManor::get(['user_id' => $this->uid]);
        $diffTime = bcsub($currentTime, $manor['last_output_time']);
        $output = UserAnimal::getOutput($this->uid, CfgAnimal::OUTPUT);
        $addCount = UserAnimal::getOutputNumber($this->uid, $diffTime, $manor['count_left']);

        Common::res(['data' => [
            'list' => $list,
            'output' => $output,
            'add_count' => $addCount,
            'steal_left' => $manor['day_steal']
        ]]);
    }

    public function getAnimalLotteryInfo()
    {
        // 获取宠物奖池信息
        $list = AnimalLottery::with(['animal'])
            ->order([
                'chance' => 'desc',
                'id' => 'asc'
            ])
            ->select();

        if (is_object($list)) $list = $list->toArray();

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
        // 获取宠物信息
        $animalId = (int)input('animal_id', 0);
        if (empty($animalId)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        $animal = CfgAnimal::get($animalId);
        if (empty($animal)) {
            Common::res(['code' => 1, 'msg' => '宠物暂未开放']);
        }

        $userAnimal = UserAnimal::get(['animal_id' => $animalId]);
        if (empty($userAnimal)) {
            Common::res(['code' =>1, 'msg' => '尚未拥有哦']);
        }

        $lv = empty($userAnimal) ? 0: $userAnimal['level'];
        $nextLv = bcadd($lv, 1);

        $lvDict = CfgAnimalLevel::getDictList((new CfgAnimalLevel()), [$lv, $nextLv], 'level', '*', ['animal_id' => $animalId]);

        $data = [
            'animal' => $animal,
            'lv' => $lvDict[$lv],
            'next_lv' => array_key_exists($nextLv, $lvDict) ? $lvDict[$nextLv]: null
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

    public function getCfgBackground()
    {
        // 获取庄园背景列表
    }

    public function useBackground()
    {
        // 使用庄园背景
    }
}