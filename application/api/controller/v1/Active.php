<?php
namespace app\api\controller\v1;

use app\api\model\ActiveLaren;
use app\api\model\Cfg;
use app\api\model\CfgTaskactivity618;
use app\api\model\CfgWealActivityTask;
use app\api\model\FanclubUser;
use app\api\model\LarenStar;
use app\api\model\LarenUser;
use app\api\model\Rec;
use app\api\model\RecActivity618;
use app\api\model\RecTaskactivity618;
use app\api\model\RecWealActivity;
use app\api\model\RecWealActivityTask;
use app\api\model\UserExt;
use app\base\controller\Base;
use app\base\service\Common;

class Active extends Base
{
    public function laren()
    {
        $this->getUser();

        // 我的爱心
        $res['myAixin'] = UserExt::where('user_id', $this->uid)->value('aixin');
        // 列表
        $res['list'] = ActiveLaren::getList($this->uid);
        // rank
        $res['rank'] = LarenUser::rank();

        $res['noticeId'] = 26;

        Common::res(['data' => $res]);
    }

    public function sendAixin()
    {
        $this->getUser();
        $active_id = $this->req('active_id', 'integer');
        $count = $this->req('count', 'integer');

        LarenUser::send($this->uid, $active_id, $count);
        Common::res();
    }

    /**返还爱心 */
    public function returnAixin()
    {
        $activeCollection = ActiveLaren::all();

        foreach ($activeCollection as $value) {
            // TODO
            LarenStar::where('active_id', $value['id'])->order('count desc')->value('star_id');
        }
    }

    /**618活动任务列表*/
    public function blessingTaskList()
    {
        $this->getUser();

        // 我的福袋幸运值
        $res['myinfo'] = UserExt::where('user_id', $this->uid)->field('blessing_num,lucky_value')->find();
        // 任务列表
        $res['list'] = (new CfgTaskactivity618())->getList($this->uid);

        $res['fanclub_id'] = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');


        Common::res(['data' => $res]);
    }

    /**618活动福气榜列表*/
    public function blessingList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);

        // 618所赠福气豆列表
        $res = UserExt::blessingList($this->uid,$page,$size);

        Common::res(['data' => $res]);
    }

    /**618活动福气领取*/
    public function getBlessingBag()
    {
        $this->getUser();
        $task_id = $this->req('task_id', 'integer');
        $task = (new CfgTaskactivity618())->get($task_id);
        if(!$task){
            Common::res(['code' => 1, 'msg' => '不存在该任务']);
        }
        $rectask=RecTaskactivity618::where(['user_id'=>$this->uid,'task_id'=>$task_id])->find();

        if($rectask){
            if($task_id==4 || $task_id==8 || $task_id==9){
                if($rectask['is_settle_times']>=1 && $rectask['done_times']>=1){
                    Common::res(['code' => 1, 'msg' => '该任务已经领取过了']);
                }
            }else{
                if($rectask['done_times']<$task['times'] || $rectask['done_times']/$task['times']-$rectask['is_settle_times']<1 ){
                    Common::res(['code' => 1, 'msg' => '该任务还不能领取']);
                }
            }
        }

        if($rectask && $task_id!=4){

            $num=($rectask['done_times']-$task['times']*$rectask['is_settle_times'])/$task['times'];
            $addnum=floor($num);

        }else{
            $addnum=1;
        }
        UserExt::addLucky($this->uid,$addnum,$task_id);

        $res=[
            "blessing_num"=>$addnum,
            "lucky_value"=>$addnum,
        ];
        Common::res(['data' => $res]);
    }

    /**618活动福袋使用*/
    public function useBlessingBag(){

        $this->getUser();
        $starid = $this->req('starid', 'integer');
        // 1金豆 2鲜花
        $type = $this->req('type', 'integer', 0);
        $danmaku = $this->req('danmaku', 'integer', 1); // 是否推送打榜弹幕

        $rec = Rec::where('user_id',$this->uid)->where('content','为爱豆打榜')->order('create_time desc')->find();
        if(0-$rec['coin']>0){
            $hot=-$rec['coin'];
        }elseif(0-$rec['flower']>0){
            $hot=-$rec['flower'];
        }
        $res = UserExt::useBlessingBag($starid, $hot, $this->uid, $type, $danmaku);

        Common::res(['data' => $res]);
    }

    /**618活动福袋日志*/
    public function logBlessingBag(){

        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $filter = $this->req('filter');
        $logList = RecActivity618::getList($this->uid, $page, $size, $filter);

        Common::res(['data' => $logList]);
    }

    /**活动任务列表*/
    public function wealTask()
    {
        $this->getUser();

        // 我的福袋幸运值
        $res['myinfo'] = UserExt::where('user_id', $this->uid)->field('bag_num,lucky')->find();
        // 任务列表
        $res['list'] = (new CfgWealActivityTask())->getList($this->uid);

        $res['fanclub_id'] = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');

        Common::res(['data' => $res]);
    }

    /**活动福气榜列表*/
    public function wealList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);

        // 618所赠福气豆列表
        $res = UserExt::luckyRank($this->uid,$page,$size);

        Common::res(['data' => $res]);
    }

    /**活动福气领取*/
    public function getwealbag()
    {
        // 检测是否开启福袋任务
        $status = Cfg::checkActiveByPathInBtnGroup (Cfg::WEAL_ACTIVE_PATH);
        if (empty($status)) {
            Common::res (['code' => 1, 'msg' => '活动已结束']);
        }
        $this->getUser();
        $task_id = $this->req('task_id', 'integer');
        $task = (new CfgWealActivityTask())->get($task_id);
        if(!$task){
            Common::res(['code' => 1, 'msg' => '不存在该任务']);
        }
        if ($task['type'] != CfgWealActivityTask::ONCE) {
            $rectask=RecWealActivityTask::where(['user_id'=>$this->uid,'task_id'=>$task_id])->find();

            if (empty($rectask)) {
                Common::res (['code' => 1, 'msg' => "还未完成该任务哦"]);
            }
        }

        $earn = (new RecWealActivityTask())->settle ($task_id, $this->uid);

        Common::res(['data' => $earn]);
    }

    /**活动福袋日志*/
    public function wealLog(){

        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $filter = $this->req('filter');
        $logList = RecWealActivity::getList($this->uid, $page, $size, $filter);

        Common::res(['data' => $logList]);
    }
}
