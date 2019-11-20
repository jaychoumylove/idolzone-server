<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserStar;
use app\api\model\Fanclub;
use app\api\model\FanclubUser;
use app\api\model\Star;
use app\api\service\User;
use think\Db;

class FansClub extends Base
{
    /**
     * 创建粉丝团
     */
    public function create()
    {
        $this->getUser();

        $res['avatar'] = $this->req('avatar', 'require');
        $res['clubname'] = $this->req('clubname', 'require');
        $res['wx'] = $this->req('wx', 'require');
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
        if (!$fid) {
            $this->getUser();
            $fid = FanclubUser::where('user_id', $this->uid)->value('fanclub_id');
        }

        $res = Fanclub::with('star')->where('id', $fid)->find();
        $res['week_rank'] = Fanclub::where('week_count', '>', $res['week_count'])->count() + 1;

        $res['mass_time'] = date('H') . ':00-' . (date('H') + 1) . ':00';
        $res['mass_total'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->sum('mass_count');
        $res['mass_people'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->count();
        $res['mass_user'] = FanclubUser::with('user')->where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->field('user_id')->limit(10)->select();

        Common::res(['data' => $res]);
    }

    public function member()
    {
        $fid = $this->req('fid', 'integer');
        $page = $this->req('page', 'integer', 1);

        $res['list'] = FanclubUser::with('User')->where('fanclub_id', $fid)->field('user_id,thisweek_count')
            ->order('thisweek_count desc')->page($page, 10)->select();
        $res['leader_uid'] = Fanclub::where('id', $fid)->value('user_id');
        Common::res(['data' => $res]);
    }

    public function list()
    {
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $keyword = $this->req('keyword');
        // 关键字
        if ($keyword) {
            $ids = Star::where('name', 'like', '%' . $keyword . '%')->column('id');
            $w = ['star_id' => ['in', $ids]];
        } else {
            $w = '1=1';
        }

        $list = Fanclub::with('star')->where($w)->whereOr('clubname', 'like', '%' . $keyword . '%')->order('week_count desc')->page($page, $size)->select();
        Common::res(['data' => $list]);
    }

    public function join()
    {
        $f_id = $this->req('id', 'integer');
        $this->getUser();

        Fanclub::joinFanclub($this->uid, $f_id);
        Common::res();
    }

    public function exit()
    {
        $this->getUser();
        $uid = $this->req('user_id', 'integer');

        if ($this->uid != $uid) {
            // 是否是团长
            $f_id = UserStar::where('user_id', $uid)->value('fanclub_id');
            $leader = Fanclub::where('id', $f_id)->value('user_id');
            if ($leader != $this->uid) Common::res(['code' => 1, 'msg' => '没有权限']);
        }

        Fanclub::exitFanclub($uid);
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
        if (date('YmdH') == $self_massTime) Common::res(['code' => 2, 'msg' => '你已参加过本次集结，请下次再来']);

        if ($type == 0) {
            $coin = 100;
        } else if ($type == 1) {
            $coin = 1000; // 看视频
        }
        // 热度+
        Fanclub::where('id', $fid)->update(['week_hot' => Db::raw('week_hot+' . $coin)]);

        FanclubUser::where('user_id', $this->uid)->update([
            'mass_time' => date('YmdH'),
            'mass_count' => $coin,
        ]);

        (new User)->change($this->uid, ['coin' => $coin], '粉丝团集结');
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

        $res['noticeId'] = 19;
        $res['fanclub'] = Fanclub::with('star')->where('id', $fid)->find();
        // 总共的能量
        $res['totalCount'] = FanclubUser::where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->sum('mass_count');
        // 参与集结用户
        $res['list'] = FanclubUser::with('user')->where('fanclub_id', $fid)->where('mass_time', date('YmdH'))->select();
        // 集结剩余时间
        $res['remainTime'] = strtotime(date('Y-m-d H:00:00', time() + 3600)) - time();
        Common::res(['data' => $res]);
    }
}
