<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;
use think\Db;

class CfgPkactive extends Base
{
    public static function settle($star_id,$rank)
    {        
        //活动是不是进行中
        if (Cfg::isPkactiveStart()){

            //查询对应的奖励
            $score = self::where('rank',$rank)->value('score');
            if(!$score) return;
            
            $isDone = StarRankPkactive::where(['star_id'=>$star_id])->update([
                'score' => Db::raw('score+' . $score)
            ]);
            
            if(!$isDone) StarRankPkactive::create([
                'star_id' => $star_id,
                'score' => $score,
            ]);
        }
    }
}
