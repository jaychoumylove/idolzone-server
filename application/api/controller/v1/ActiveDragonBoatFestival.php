<?php
namespace app\api\controller\v1;

use app\api\model\ActiveDragonBoatFestival as ActiveDragonBoatFestivalModel;
use app\api\model\ActiveDragonBoatFestivalFanclub;
use app\api\model\Cfg;
use app\api\model\Fanclub;
use app\api\model\FanclubUser;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class ActiveDragonBoatFestival extends Base
{

    /**端午活动列表*/
    public function index()
    {
        $this->getUser();
        $res['difference_first'] = 0;//距离第一名差值
        $res['join_active_id'] = 0;//加入的场次id
        $res['is_admin'] = 0;//是否团长管理员
        $res['notice_id']=48;//奖励说明id
        $res['myClubInfo']='';
        $res['list'] = ActiveDragonBoatFestivalModel::getList();//比赛列表
        $res['fanclub_id'] = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $res['time_text'] = $this->timeText('/pages/active/dragon_boat_festival');//起始结束时间文本

        if($res['fanclub_id']){
            $res['join_active_id']= ActiveDragonBoatFestivalFanclub::where('fanclub_id',$res['fanclub_id'])->value('active_id');
            $isLeader = FanclubUser::isLeader($this->uid);
            $isAdmin = FanclubUser::isAdmin($this->uid);
            if($res['join_active_id']!=0){
                $res['myClubInfo']= $this->myClubInfo($res['fanclub_id'],$res['join_active_id']);
            }
            if($isLeader || $isAdmin){
                $res['is_admin'] = 1;
            }
        }

        Common::res(['data' => $res]);
    }

    /**端午活动粉丝团列表*/
    public function fanclubList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);
        $active_id = $this->req('active_id', 'integer');
        $res['notice_id']=48;//奖励说明id
        $res['is_exit'] = false;//是否可以退出
        $fanclub_id = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $is_join= ActiveDragonBoatFestivalFanclub::where('fanclub_id',$fanclub_id)->where('active_id',$active_id)->count();
        if($is_join){
            $isLeader = FanclubUser::isLeader($this->uid);
            $isAdmin = FanclubUser::isAdmin($this->uid);
            if($isLeader || $isAdmin){
                $res['is_exit'] = true;
            }
            $res['myClubInfo']= $this->myClubInfo($fanclub_id,$active_id);
        }

        $res['active_info'] = ActiveDragonBoatFestivalModel::get($active_id);
        $res['active_info']['time_text'] = $this->timeText('/pages/active/dragon_boat_festival');//起始结束时间文本
        $res['list'] = ActiveDragonBoatFestivalFanclub::getList($this->uid,$active_id,$page,$size);


        Common::res(['data' => $res]);
    }

    /**端午活动粉丝团用户列表*/
    public function fanclubUserList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 15);
        $active_id = $this->req('active_id', 'integer');

        $res = ActiveDragonBoatFestivalFanclub::getUserList($this->uid,$page,$size);
        $res['active_info'] = ActiveDragonBoatFestivalModel::get($active_id);

        Common::res(['data' => $res]);
    }

    /**加入端午活动*/
    public function joinIt()
    {
        $this->getUser();
        $active_id = $this->req('active_id', 'integer');

        $fanclub_info = FanclubUser::with('fanclub')->where('user_id', $this->uid)->find();
        $this->check($this->uid,$fanclub_info['fanclub_id'],'create');

        Db::startTrans();
        try {

            ActiveDragonBoatFestivalFanclub::create([
                'fanclub_id'=>$fanclub_info['fanclub_id'],
                'fanclub_name'=>$fanclub_info['fanclub']['clubname'],
                'fanclub_avatar'=>$fanclub_info['fanclub']['avatar'],
                'user_id'=>$this->uid,
                'active_time'=>time(),
                'active_id'=>$active_id,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res([]);
    }

    /**退出端午活动*/
    public function exitIt()
    {
        $this->getUser();

        $fanclub_id = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $this->check($this->uid,$fanclub_id,'exit');

        Db::startTrans();
        try {

            FanclubUser::where('fanclub_id',$fanclub_id)->update([
                'dragon_boat_festival_hot'=>0
            ]);
            ActiveDragonBoatFestivalFanclub::where('fanclub_id',$fanclub_id)->delete(true);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res([]);
    }

    public function check($uid,$fanclub_id,$type='create'){

        if(Cfg::isActiveDragonBoatFestivalStart()==false)Common::res(['code' => 1, 'msg' => '未在活动时间内']);
        if(!$fanclub_id)Common::res(['code' => 1, 'msg' => '请先加入粉丝团']);
        $is_join= ActiveDragonBoatFestivalFanclub::where('fanclub_id',$fanclub_id)->count();
        $isLeader = FanclubUser::isLeader($uid);
        $isAdmin = FanclubUser::isAdmin($uid);
        if($type=='create'){
            if($is_join)Common::res(['code' => 1, 'msg' => '粉丝团已加入活动']);
            if(!$isLeader && !$isAdmin) Common::res(['code' => 1, 'msg' => '只有团长和管理员才能加入活动']);
        }else if($type=='exit'){
            if(!$is_join)Common::res(['code' => 1, 'msg' => '请先参加活动']);
            if(!$isLeader && !$isAdmin) Common::res(['code' => 1, 'msg' => '只有团长和管理员才能退出活动']);
        }

    }

    public function myClubInfo($fanclub_id,$active_id){

        $first_total_count= ActiveDragonBoatFestivalFanclub::where('active_id',$active_id)->order('total_count desc,create_time asc')->value('total_count');
        $myclub_total_count= ActiveDragonBoatFestivalFanclub::where('active_id',$active_id)->where('fanclub_id',$fanclub_id)->value('total_count');
        $star_info= Fanclub::with('star')->where('id',$fanclub_id)->field('star_id')->find();

        $myClubInfo= ActiveDragonBoatFestivalFanclub::where('fanclub_id',$fanclub_id)->field('id,fanclub_id,fanclub_name,fanclub_avatar,total_count,active_id')->find();
        $myClubInfo['difference_first'] = $first_total_count-$myclub_total_count>0?($first_total_count-$myclub_total_count):0;
        $myClubInfo['star_name']=$star_info['star']['name'];
        $active_fanclubs= ActiveDragonBoatFestivalFanclub::where('active_id',$active_id)->order('total_count desc,create_time asc')->column('fanclub_id');
        $myClubInfo['rank']=array_search($fanclub_id,$active_fanclubs)+1;

        return $myClubInfo;
    }

    public function timeText($path){
        $btn_cfg=Cfg::getCfg('btn_cfg');
        $groupList=$btn_cfg['group'];
        $text='';
        foreach ($groupList as $value){
            if($value['path']==$path){
                $start_date=date("m-d",strtotime($value['start_time']));
                $start_date_arr=explode("-",$start_date);
                $end_date=date("m-d",strtotime($value['end_time']));
                $end_date_arr=explode("-",$end_date);
                $text=$start_date_arr[0].'月'.$start_date_arr[1].'日-'.$end_date_arr[0].'月'.$end_date_arr[1].'日';
            }
        }
        return $text;
    }
}
