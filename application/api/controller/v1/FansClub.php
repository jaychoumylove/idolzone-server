<?php
namespace app\api\controller\v1;

use app\api\model\CfgWealActivityTask;
use app\api\model\RecTaskactivity618;
use app\api\model\RecWealActivityTask;
use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserStar;
use app\api\model\Fanclub;
use app\api\model\FanclubBox;
use app\api\model\FanclubBoxUser;
use app\api\model\FanclubUser;
use app\api\model\CfgTaskgiftCategory;
use app\api\model\Star;
use app\api\service\User;
use think\Db;
use app\api\service\FanclubTask;
use app\api\model\RecTaskfather;
use app\api\model\CfgUserLevel;
use app\base\service\WxAPI;
use app\api\model\FanclubApplyUser;

class FansClub extends Base
{
    /**
     * 创建粉丝团
     */
    public function create()
    {

        $res['avatar'] = $this->req('avatar', 'require');
        $res['clubname'] = $this->req('clubname', 'require');
        $res['wx'] = $this->req('wx', 'require');
        $this->getUser();

        if(CfgUserLevel::getLevel($this->uid)<9) Common::res(['code' => 1, 'msg' => '粉丝等级需达到9级']);
        
        (new WxAPI())->msgCheck($res['clubname']);
        $res['user_id'] = $this->uid;
        $res['star_id'] = UserStar::getStarId($this->uid);

        Db::startTrans();
        try {
            $new = Fanclub::create($res);
            Fanclub::joinFanclub($this->uid, $new['id']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res();
    }

    public function info()
    {
        $fid = $this->req('fid', 'integer', 0);

        $this->getUser();
        if (!$fid) $fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        if (!$fid) Common::res(['data' => ['redirect' => true]]);

        $res = Fanclub::with(['star','user'])->where('id', $fid)->find();
        unset($res['wx']);
        $res['week_rank'] = Fanclub::where('week_count', '>', $res['week_count'])->count() + 1;

        $res['mass_time'] = date('H') . ':00-' . (date('H') + 1) . ':00';
        $res['mass_total'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->sum('mass_count');
        $res['mass_people'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->count();
        $res['mass_user'] = FanclubUser::with('user')->where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->field('user_id')->limit(5)->select();
        $res['new_user'] = FanclubUser::with('user')->where('fanclub_id', $fid)->whereTime('create_time', 'Today')->field('user_id')->limit(5)->select();
        $res['new_people'] = count($res['new_user']);
        $res['apply_count'] = FanclubApplyUser::where('fanclub_id', $fid)->where('status', 1)->count();

        $res['leader'] = FanclubUser::isLeader($this->uid);
        $res['admin'] = FanclubUser::isAdmin($this->uid);

        Common::res(['data' => $res]);
    }

    public function member()
    {
        $this->getUser();
        $fid = $this->req('fid', 'integer');
        $page = $this->req('page', 'integer', 1);
        $field  = input('field');
        $keyword  = input('keyword');
                
        $w = $keyword ? ['nickname'=>['like', '%'.$keyword.'%']]:'1=1';
        $res['list'] = Db::name('fanclub_user')
            ->alias('f')
            ->join('user u', 'u.id = f.user_id','LEFT')
            ->field('avatarurl,nickname,user_id,admin,' . $field . ' as hot,last' . $field . ' as lastweek_hot')
            ->where($w)
            ->where('fanclub_id', $fid)
            ->where('f.delete_time', 'NULL')
            ->order($field . ' desc')->page($page, 20)->select();
        
        foreach ($res['list'] as &$value){
            $value['level'] = CfgUserLevel::getLevel($value['user_id']);
        }

        $res['leader_uid'] = Fanclub::where('id', $fid)->value('user_id');
        $res['admin']=FanclubUser::isAdmin($this->uid);
        $this->getUser();
        $res['my'] = FanclubUser::getMyRankInfo($this->uid, $fid, $field);

        Common::res(['data' => $res]);
    }
    
    public function list()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $keyword = $this->req('keyword');
        $field = $this->req('field', 'require');
        $this->getUser();
        
        $list = Fanclub::getList($keyword, $field, $page, $size);
        
        foreach ($list as &$value) {
            if(isset($value['id'])) $value['mystatus'] = FanclubApplyUser::where([
                        'fanclub_id' => $value['id'],
                        'user_id' => $this->uid
                    ])->value('status');
        }
        
        Common::res([
            'data' => $list
        ]);
    }

    public function exit()
    {
        $this->getUser();
        $uid = $this->req('user_id', 'integer');

        Fanclub::exitFanclub($this->uid,$uid);
        Common::res();
    }
    public function upAdmin(){
        $this->getUser();
        $uid= $this->req('user_id','integer');
        $admin= $this->req('admin','integer');
        $res=Fanclub::upAdmin($this->uid,$uid,$admin);
        if($res) Common::res(['msg'=>'操作成功']);
    }

    public function edit()
    {
        $fid = $this->req('fid', 'require');
        $res['avatar'] = $this->req('avatar', 'require');
        $res['clubname'] = $this->req('clubname', 'require');
        $res['wx'] = $this->req('wx', 'require');
        
        //安全检测
        (new WxAPI())->msgCheck($res['clubname']);

        $this->getUser();
        Db::startTrans();
        try {

            (new User)->change($this->uid, ['stone' => -100], '修改粉丝团信息');
            Fanclub::where('id', $fid)->update($res);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res();
    }

    /**粉丝团集结 */
    public function joinMass()
    {
        $type = $this->req('type', 'integer', 0);
        $fid = $this->req('fid', 'integer');
        $this->getUser();

        $self_fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        if ($self_fid != $fid) Common::res(['code' => 1, 'msg' => '未加入该粉丝团']);
        $self_massTime = Db::name('fanclub_user')->where('user_id', $this->uid)->order('mass_time desc')->value('mass_time');
        if (date('YmdH') == $self_massTime) Common::res(['code' => 2, 'msg' => '你已参加过本次集结，请下一个小时再来']);

        if ($type == 0) {
            $coin = 100;
        } else if ($type == 1) {
            $coin = 1000; // 看视频
        }
        Db::startTrans();
        try {

            // 热度+
            $isDone =FanclubUser::where('mass_time IS NULL OR mass_time < '.date('YmdH'))->where('user_id', $this->uid)->where('fanclub_id', $fid)->update([
                'mass_time' => date('YmdH'),
                'mass_count' => $coin,
                'week_hot' => Db::raw('week_hot+' . $coin),
            ]);
            if (!$isDone) Common::res(['code' => 2, 'msg' => '你已参加过本次集结，请下一个小时再来2']);
            
            Fanclub::where('id', $fid)->update(['week_hot' => Db::raw('week_hot+' . $coin)]);

            RecTaskactivity618::addOrEdit($this->uid, 3,1);

            RecTaskfather::addRec($this->uid, [3, 14, 25, 36]);

            (new User)->change($this->uid, ['coin' => $coin, 'point' => $coin], '集结');
            UserStar::changeHandle($this->uid, 'mass');

            RecWealActivityTask::setTask ($this->uid, 1, CfgWealActivityTask::FANS_CLUB_MASS);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        Common::res(['data' => $coin]);
    }

    /**团集结信息 */
    public function mass()
    {
        $referrer = $this->req('referrer');
        $this->getUser();

        $myStar = UserStar::getStarId($this->uid);
        $self_fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $fid = FanclubUser::where('user_id', $referrer)->value('fanclub_id');

        if (!$myStar) {
            // 没有加入圈子
            $res['userStatus'] = 1;
        } else if (!$self_fid) {
            // 没有加入粉丝团
            $res['userStatus'] = 2;
        } else if ($self_fid != $fid) {
            // 已经加入别的粉丝团
            $res['userStatus'] = 3;
        } else {
            $res['userStatus'] = 0;
        }

        $res['noticeId'] = 10;
        $res['fanclub'] = Fanclub::with('star')->where('id', $fid)->find();
        // 总共的能量
        $res['totalCount'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->sum('mass_count');
        // 参与集结用户
        $res['list'] = FanclubUser::with('user')->where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->order('week_count desc')->select();
        // 集结剩余时间
        $res['remainTime'] = strtotime(date('Y-m-d H:00:00', time() + 3600)) - time();
        Common::res(['data' => $res]);
    }

    public function mybox()
    {
        $this->getUser();

        $fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $res = FanclubBox::getBoxList($fid, $this->uid);

        $res['noticeId'] = 12;
        $res['isJoinFanclub'] = FanclubUser::where('user_id', $this->uid)->value('id');

        //$taskGiftCategory = CfgTaskgiftCategory::getCategoryMore($this->uid);
        $res['signGift_title'] = '新人礼包';//$taskGiftCategory['all_title'];
        $res['signGift_tips'] = '';//$taskGiftCategory['all_tips'];
        Common::res(['data' => $res]);
    }

    /**发宝箱 */
    public function sendbox()
    {
        $type = $this->req('type', 'integer', 0); // 0钻石 1积分 2鲜花
        $consume = $this->req('consume', 'integer'); // 消耗
        $people = $this->req('people', 'integer', 10); // 人数
        if($people>50) Common::res(['code' => 1, 'msg' => '不能超过50人']);
        
        $this->getUser();        
        FanclubBox::sendbox($this->uid, $type, $consume, $people);
        
        Common::res();
    }

    public function getBox()
    {
        $box_id = $this->req('box_id', 'integer');

        $this->getUser();

        FanclubBoxUser::openBox($this->uid, $box_id);

        $res['info'] = FanclubBox::with('user')->where('id', $box_id)->find();

        $res['self'] = FanclubBoxUser::with('user')->where('box_id', $box_id)->where('user_id', $this->uid)->find();
        if (!$res['self']) $res['self'] = ['count' => 0];

        $res['list'] = FanclubBoxUser::with('user')->where('box_id', $box_id)->order('id desc')->select();
        // 已领取
        $res['info']['isEarn'] = FanclubBoxUser::where('box_id', $box_id)->sum('count');
        // 手气最佳
        $res['lucky'] = FanclubBoxUser::where('box_id', $box_id)->order('count desc')->value('user_id');
        // // 奖品type 1coin
        // $res['award_type'] = RecLottery::with(['lottery'])->where('id', $box_id)->find()['lottery']['type'];

        Common::res(['data' => $res]);
    }



    public function task()
    {
        $fid = $this->req('fid', 'integer', 0);
        $type = $this->req('type', 'integer');

        $this->getUser();
        if (!$fid) $fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');

        if ($type == 1) {
            // 每周任务
            $list = (new FanclubTask())->checkTask($fid, $type);
        }
        Common::res(['data' => $list]);
    }

    public function settle()
    {
        $task_id = $this->req('task_id', 'integer');
        $this->getUser();

        $earn = (new FanclubTask())->settle($task_id, $this->uid);
        Common::res([
            'data' => $earn
        ]);
    }
    
    public function editNotice()
    {
        $notice = $this->req('notice', 'require');
        $fid = $this->req('fid', 'require');
        
        (new WxAPI())->msgCheck($notice);
        
        $this->getUser();
        Fanclub::where(['user_id'=>$this->uid,'id'=>$fid])->update(['notice' => $notice]);
        Common::res();
    }
    

    public function apply()
    {
        $f_id = $this->req('id', 'integer');
        $this->getUser();
        
        $hasJoined = FanclubUser::where('user_id', $this->uid)->count();
        if ($hasJoined)
            Common::res([
                'code' => 1,
                'msg' => '你已经粉丝团成员'
            ]);
    
        $apply_count = FanclubApplyUser::where('user_id',$this->uid)->where('status','1')->count();
        if ($apply_count >= 2)
            Common::res([
                'code' => 1,
                'msg' => '申请数不能超过2个'
            ]);
    
            $res['fanclub_id'] = $f_id;
            $res['user_id'] = $this->uid;
            
            $isDone = FanclubApplyUser::where($res)->update([
                'status' => 1
            ]);
            if (! $isDone){
                try {

                    FanclubApplyUser::create($res);

                } catch (\Exception $e) {

                    Common::res([
                        'code' => 1,
                        'msg' => '已经在申请中，请等待团长审核'
                    ]);
                }
            }

            Common::res();
    }
    
    public function applylist()
    {
        $this->getUser();
//        $f_id=input('fid');

        $f_id = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        $list = FanclubApplyUser::with('user')->where([
            'fanclub_id' => $f_id,
            'status' => 1
        ])->select();
//        echo FanclubApplyUser::getLastSql();
        foreach ($list as &$value) {
            $value['user_level'] = CfgUserLevel::getLevel($value['user_id']);
        }

        Common::res([
            'data' => $list
        ]);
    }
    
    public function applydeal()
    {
        $f_id = $this->req('fid', 'integer');
        $uid = $this->req('uid', 'integer');
        $status = $this->req('status', 'integer');
        $this->getUser();
    
        $leader = Fanclub::where('id', $f_id)->value('user_id');
        $admin=FanclubUser::isAdmin($this->uid);
        if ($leader != $this->uid && !$admin)
            Common::res([
                'code' => 1,
                'msg' => '没有权限'
            ]);
    
            if ($status == - 1) // 拒绝
                FanclubApplyUser::where([
                    'fanclub_id' => $f_id,
                    'user_id' => $uid
                ])->update([
                    'status' => $status
                ]);
    
                elseif ($status == 2) { // 允许
    
                    Db::startTrans();
                    try {
    
                        FanclubApplyUser::where([
                            'fanclub_id' => $f_id,
                            'user_id' => $uid
                        ])->delete();
                        Fanclub::joinFanclub($uid, $f_id);
    
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        Common::res([
                            'code' => 400,
                            'msg' => $e->getMessage()
                        ]);
                    }
                }
                Common::res();
    }
    
    public function enter()
    {
        $user_id = $this->req('user_id', 'integer', 0);
        
        $fid = FanclubUser::where('user_id', $user_id)->value('fanclub_id');
        if (! $fid)
            Common::res([
                'data' => [
                    'redirect' => true
                ]
            ]);
    
        $res = Fanclub::with('star')->where('id', $fid)->find();
        $res['users'] = FanclubUser::with('User')->where('fanclub_id', $fid)
            ->field('user_id')
            ->order('week_hot desc')
            ->limit(6)
            ->select();

        Common::res([
            'data' => $res
        ]);
    }
}
