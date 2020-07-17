<?php

namespace app\api\model;

use app\base\model\Base;

class CfgWealActivityTask extends Base
{
    public function getList($uid)
    {
        $list = self::select();
        foreach ($list as $key=>$value){
            $rectask=RecWealActivityTask::where(['user_id'=>$uid,'task_id'=>$value['id']])->find();
            if($rectask){
                $list[$key]['done_times']=$rectask['done_times'];

                if(($value['id']==4 || $value['id']==8 || $value['id']==9) && $rectask['is_settle_times']>=1){
                    $list[$key]['status']=2;
                }else{
                    if($rectask['done_times']>=$value['times'] && $rectask['done_times']/$value['times']-$rectask['is_settle_times']>=1 ){
                        $list[$key]['status']=1;
                    }else{
                        $list[$key]['status']=0;
                    }
                }
            }else{
                $list[$key]['done_times']=0;
                $list[$key]['status']=0;
            }
        }

        return $list;
    }
}
