<?php


namespace app\api\controller\v1;


use app\api\model\CfgAnimal;
use app\api\model\UserAnimal;
use app\base\controller\Base;

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
            $list = CfgAnimal::with(['user_animal'])
                ->select();
        }

        if ($type == 'yet') {
            // 尚未拥有
            $animalIds = UserAnimal::where('user_id', $this->uid)
                ->where('lock', 0)
                ->column('animal_id');

            $list = CfgAnimal::with(['user_animal'])
                ->where('id', 'not in', $animalIds)
                ->select();
        }


    }

    public function getAnimalLotteryInfo()
    {
        // 获取宠物奖池信息
    }

    public function upAnimal()
    {
        // 宠物升级
    }

    public function lotteryAnimal()
    {
        // 抽奖
    }

    public function unLockAnimal()
    {
        // 解锁宠物
    }

    public function getAnimalInfo()
    {
        // 获取宠物信息
    }

    public function animalSteal()
    {
        // 宠物偷豆
    }

    public function animalOutput()
    {
        // 宠物产豆收集
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