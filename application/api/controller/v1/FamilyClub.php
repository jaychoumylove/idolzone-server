<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserStar;
use app\api\model\Family;
use app\api\model\FamilyUser;
use app\api\model\Star;
use app\api\service\User;
use think\Db;
use app\api\model\FamilyApplyUser;
use app\api\model\CfgUserLevel;
use app\api\model\Cfg;
use app\base\service\WxAPI;

class FamilyClub extends Base
{

    /**
     * 创建家族
     */
    public function create()
    {
        $res['avatar'] = $this->req('avatar', 'require');
        $res['clubname'] = $this->req('clubname', 'require');
        
        //非法词检测
        (new WxAPI())->msgCheck($res['clubname']);

        $this->getUser();
        $res['user_id'] = $this->uid;
        $res['star_id'] = UserStar::getStarId($this->uid);
        
        if(Family::where(['clubname'=>$res['clubname']])->count()){
            Common::res([
                'code' => 1,
                'msg' => '名字重复'
            ]);
        }
        
        Db::startTrans();
        try {
            $new = Family::create($res);
            Family::joinFamily($this->uid, $new['id']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
        
        Common::res();
    }

    public function info()
    {
        $fid = $this->req('fid', 'integer', 0);
        
        $this->getUser();
        if (! $fid)
            $fid = FamilyUser::where('user_id', $this->uid)->value('family_id');
        if (! $fid)
            Common::res([
                'data' => [
                    'redirect' => true
                ]
            ]);
        
        $res = Family::with('star')->where('id', $fid)->find();
        $res['week_rank'] = Family::where('thisweek_count', '>', $res['thisweek_count'])->count() + 1;
        $res['leader'] = FamilyUser::isLeader($this->uid);
        $res['allow_count'] = Family::getMaxMen($fid);
        
        // 判断是否可领取
        $family_switch = Cfg::where('key', 'family_switch')->value('value');
        $family_switch = json_decode($family_switch, true);
        $res['cansettle'] = (time() <= $family_switch['reback_end_time']) && $res['lastweek_count'] && FamilyUser::where('user_id', $this->uid)->value('lastweek_count') && ! FamilyUser::where('user_id', $this->uid)->whereTime('settle_time', 'week')->count();
        
        Common::res([
            'data' => $res
        ]);
    }

    public function enter()
    {
        $user_id = $this->req('user_id', 'integer', 0);
        
        $fid = FamilyUser::where('user_id', $user_id)->value('family_id');
        if (! $fid)
            Common::res([
                'data' => [
                    'redirect' => true
                ]
            ]);
        
        $res = Family::with('star')->where('id', $fid)->find();
        $res['allow_count'] = Family::getMaxMen($fid);
        $res['users'] = FamilyUser::with('User')->where('family_id', $fid)
            ->field('user_id')
            ->select();
        
        Common::res([
            'data' => $res
        ]);
    }

    public function member()
    {
        $fid = $this->req('fid', 'integer');
        $page = $this->req('page', 'integer', 1);
        
        $res['list'] = FamilyUser::with('User')->where('family_id', $fid)
            ->field('user_id,thisweek_count as hot')
            ->order('thisweek_count desc')
            ->page($page, 10)
            ->select();
        foreach ($res['list'] as &$value) {
            $value['level'] = CfgUserLevel::getLevel($value['user_id']);
        }
        
        $res['leader_uid'] = Family::where('id', $fid)->value('user_id');
        
        Common::res([
            'data' => $res
        ]);
    }

    public function rank()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $keyword = $this->req('keyword');
        $field = $this->req('field', 'require');
        
        $list = Family::getList($keyword, $field, $page, $size);
        
        $this->getUser();
        
        foreach ($list as &$value) {
            if ($value['mem_count'] >= Family::getMaxMen($value['id']))
                $value['mystatus'] = - 2;
            else
                $value['mystatus'] = FamilyApplyUser::where([
                    'family_id' => $value['id'],
                    'user_id' => $this->uid
                ])->value('status');
        }
        
        Common::res([
            'data' => $list
        ]);
    }

    public function apply()
    {
        $f_id = $this->req('id', 'integer');
        $this->getUser();
        
        $apply_count = FamilyApplyUser::where('user_id',$this->uid)->count();
        if ($apply_count >= 10)
            Common::res([
                'code' => 1,
                'msg' => '申请数不能超过10个'
            ]);

        $res['family_id'] = $f_id;
        $res['user_id'] = $this->uid;
        
        $mem_count = Family::where('id', $f_id)->value('mem_count');
        if ($mem_count >= Family::getMaxMen($f_id))
            Common::res([
                'code' => 1,
                'msg' => '该家族已满员'
            ]);
        
        $isDone = FamilyApplyUser::where($res)->update([
            'status' => 1
        ]);
        if (! $isDone)
            FamilyApplyUser::create($res);
        
        Common::res();
    }

    public function applylist()
    {
        $this->getUser();
        
        $f_id = Family::where('user_id', $this->uid)->value('id');
        $list = FamilyApplyUser::with('user')->where([
            'family_id' => $f_id,
            'status' => 1
        ])->select();
        
        foreach ($list as &$value) {
            $value['level'] = CfgUserLevel::getLevel($value['user_id']);
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
        
        $leader = Family::where('id', $f_id)->value('user_id');
        if ($leader != $this->uid)
            Common::res([
                'code' => 1,
                'msg' => '没有权限'
            ]);
        
        if ($status == - 1) // 拒绝
            FamilyApplyUser::where([
                'family_id' => $f_id,
                'user_id' => $uid
            ])->update([
                'status' => $status
            ]);
        
        elseif ($status == 2) { // 允许
            
            Db::startTrans();
            try {
                
                FamilyApplyUser::where([
                    'family_id' => $f_id,
                    'user_id' => $uid
                ])->delete();
                Family::joinFamily($uid, $f_id);
                
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

    public function join()
    {
        $f_id = $this->req('id', 'integer');
        $this->getUser();
        
        Family::joinFamily($this->uid, $f_id);
        Common::res();
    }

    public function quit()
    {
        $this->getUser();
        $uid = $this->req('user_id', 'integer');
        
        Family::exitFamily($this->uid,$uid);
        Common::res();
    }

    public function edit()
    {
        $fid = $this->req('fid', 'require');
        $res['avatar'] = $this->req('avatar', 'require');
        $res['clubname'] = $this->req('clubname', 'require');
        
        //非法词检测
        (new WxAPI())->msgCheck($res['clubname']);
        
        $this->getUser();
        Db::startTrans();
        try {
            
            (new User())->change($this->uid, [
                'stone' => - 100
            ], '修改家族信息');
            Family::where('id', $fid)->update($res);
            
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res([
                'code' => 400,
                'msg' => $e->getMessage()
            ]);
        }
        
        Common::res();
    }

    public function settle()
    {
        $this->getUser();
        
        $earn = Family::settle($this->uid);
        Common::res([
            'data' => $earn
        ]);
    }
}
