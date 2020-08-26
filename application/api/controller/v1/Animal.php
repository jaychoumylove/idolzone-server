<?php


namespace app\api\controller\v1;


use app\api\model\AnimalLottery;
use app\api\model\CfgAnimal;
use app\api\model\CfgAnimalLevel;
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
        // 获取宠物列表
        if ($type == 'already') {
            // 已经拥有
            $list = UserAnimal::with(['animal'])
                ->where('user_id', $this->uid)
                ->where('lock', 0)
                ->select();
        }

        if ($type == 'all') {
            // 所有
            $list = CfgAnimal::all(null, ['scrap']);
        }

        if ($type == 'yet') {
            // 尚未拥有
            $animalIds = UserAnimal::where('user_id', $this->uid)
                ->where('lock', 0)
                ->column('animal_id');

            $list = CfgAnimal::with(['scrap'])
                ->where('id', 'not in', $animalIds)
                ->select();
        }

        if (is_object($list)) $list = $list->toArray();

        Common::res(['data' => $list]);
    }

    public function getAnimalLotteryInfo()
    {
        // 获取宠物奖池信息
        $list = AnimalLottery::order([
            'chance' => 'desc',
            'id' => 'asc'
        ])->select();

        if (is_object($list)) $list = $list->toArray();

        Common::res(['data' => $list]);
    }

    public function upAnimal()
    {
        // 宠物升级
        $this->getUser();
        $animal = (int)input('animal', 0);
        if (empty($animal)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        UserAnimal::lvUp($this->uid, $animal);

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
        if (empty($animal)) {
            Common::res(['code' => 1, 'msg' => '请选择宠物']);
        }

        $animal = CfgAnimal::get($animalId);
        if (empty($animal)) {
            Common::res(['code' => 1, 'msg' => '宠物暂未开放']);
        }

        $userAnimal = UserAnimal::get(['animal_id' => $animalId]);

        $lv = empty($userAnimal) ? 0: $userAnimal['level'];
        $nextLv = bcadd($lv, 1);

        $lvDict = CfgAnimalLevel::getDictList((new CfgAnimalLevel()), [$lv, $nextLv], 'id');

        $data = [
            'animal' => $animal,
            'lv' => $lvDict[$lv],
            'next_lv' => $lvDict[$nextLv]
        ];

        Common::res(compact('data'));
    }

    public function animalSteal()
    {
        // 宠物偷豆
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