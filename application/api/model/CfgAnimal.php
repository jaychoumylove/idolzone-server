<?php


namespace app\api\model;


use app\base\model\Base;

class CfgAnimal extends Base
{
    public function scrap()
    {
        return $this->hasOne('CfgAnimalScrap', 'exchange', 'id')->field('name');
    }
}