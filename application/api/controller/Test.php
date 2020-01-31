<?php

namespace app\api\controller;


use app\base\controller\Base;
use think\Db;
use app\base\service\Common;
use app\api\model\CfgTaskgiftCategory;
use app\api\model\RecTaskgift;
use app\api\model\CfgTaskgift;
use app\api\model\CfgBadge;
use app\api\model\Prop;
use app\api\model\CfgHeadwear;

class Test extends Base
{

    public function getToken()
    {
        echo Common::setSession(input('uid') / 1234);
    }

    public function getUid()
    {
        echo Common::getSession(input('token'));
    }

    public function index()
    {
        
    }
    

    // 冬至日活动
    public function activeOn()
    {
        
        //$editTaskId1 = 30;//新人礼包奖励，任务ID
        $editTaskId2 = 31;//新人礼包奖励，任务ID
        //$badge1 = CfgBadge::where('id',56)->field('id,bimg as img,name')->find();
        Db::name('cfg_badge')->where('id',59)->update(['delete_time'=>NULL]);
        $badge2 = Db::name('cfg_badge')->where('id',59)->field('id,bimg as img,name')->find();
        $giftTask_startTime = '2020-01-20 00:00:00'; //新人礼包开始时间
        $giftTask_endTime = '2020-01-31 23:59:59'; //新人礼包结束时间
        $propId = [14];  //积分兑换冬至徽章开启
    
        //判断活动是否已开始
        $nowdate = date('Y-m-d H:i:s');
        $active_exist = CfgTaskgiftCategory::where('id', 3)->where('start_time', '<=', $nowdate)->where('end_time', '>=', $nowdate)->value('count(1)');
        if($active_exist) Common::res(['code' => 400,'msg' => '活动已经开始']);
    
        Db::startTrans();
        try {
            //清除历史数据
            RecTaskgift::where('cid',3)->delete();
            
            //设置礼包启动时间
            Db::name('cfg_taskgift_category')->where('id', 3)->update(['name'=>'春节礼包','start_time'=>$giftTask_startTime,'end_time'=>$giftTask_endTime,'delete_time'=>NULL]);
    
            //增加徽章奖励
            //             $awards['badge'] = $badge1;
            //             $update = ['awards'=>json_encode($awards)];
            //             CfgTaskgift::where('id', $editTaskId1)->update($update);
            $awards['badge'] = $badge2;
            $update = ['awards'=>json_encode($awards),'title'=>'累计充值500','count'=>500,'delete_time'=>NULL];
            Db::name('cfg_taskgift')->where('id', $editTaskId2)->update($update);
            CfgTaskgift::where('id','in',[28,29,30,32,33,34,35])->update(['delete_time'=>date('Y-m-d H:i:s')]);
    
            //开启积分商城冬至徽章兑换
            Db::name('prop')->where('id','in',$propId)->update(['delete_time'=>NULL]);
    
            //开启新年头饰
            CfgHeadwear::where('sort','in',[95,96])->update(['delete_time'=>date('Y-m-d H:i:s')]);
            Db::name('cfg_headwear')->where('sort','in',[94])->update(['delete_time'=>NULL]);
    
    
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }
        Common::res(['code' => 0,'msg' => '操作成功']);
    }
    
    // 冬至日活动
    public function activeOff()
    {
    
        //$editTaskId1 = 30;//新人礼包奖励，任务ID
        $editTaskId2 = 31;//新人礼包奖励，任务ID
        $propId = [14];  //积分兑换冬至徽章关闭
    
        //判断活动是否已结束
        $nowdate = date('Y-m-d H:i:s');
        $active_end = CfgTaskgiftCategory::where('id', 3)->where('end_time', '<', $nowdate)->value('count(1)');
        if(!$active_end) Common::res(['code' => 400,'msg' => '活动还未截止']);
    
        Db::startTrans();
        try {
            //取消徽章奖励
            //             $update = ['awards'=>'{"coin":100000,"stone":10,"trumpet":10}'];
            //             CfgTaskgift::where('id', $editTaskId1)->update($update);
            $update = ['awards'=>'{"coin":500000,"stone":55,"trumpet":50}','title'=>'累计充值500','count'=>500,'delete_time'=>date('Y-m-d H:i:s')];
            CfgTaskgift::where('id', $editTaskId2)->update($update);
    
            //关闭积分商城冬至徽章兑换
            Prop::where('id','in',$propId)->update(['delete_time'=>date('Y-m-d H:i:s')]);
            CfgTaskgiftCategory::where('id', 3)->update(['start_time'=>NULL,'end_time'=>NULL,'delete_time'=>date('Y-m-d H:i:s')]);
    
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            return 'rollBack:' . $e->getMessage();
        }
    
        Common::res(['code' => 0,'msg' => '操作成功']);
    }
    
}
