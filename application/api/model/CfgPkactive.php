<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;
use think\Db;

class CfgPkactive extends Base
{
    public static function settle($pkTime)
    {
        //活动是不是进行中
        if (Cfg::getStatus('pkactive_date')){

            $res = PkUserRank::where('last_pk_time',$pkTime)->order('last_pk_count desc,update_time asc')->limit(10)->field('id,mid')->select();
            foreach ($res as $key=>$value){
                //查询对应的奖励
                $score = self::where('rank',$key+1)->value('score');
                if(!$score) return;

                PkUserRank::where('id',$value['id'])->update([
                    'pkactive_score' => Db::raw('pkactive_score+' . $score)
                ]);

                $isDone = StarRankPkactive::where(['star_id'=>$value['mid']])->update([
                    'score' => Db::raw('score+' . $score)
                ]);

                if(!$isDone) StarRankPkactive::create([
                    'star_id' => $value['mid'],
                    'score' => $score,
                ]);
            }
        }
    }
}
