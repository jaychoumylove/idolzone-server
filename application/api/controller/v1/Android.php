<?php


namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserStar;
use app\api\service\Star as StarService;
use app\base\controller\Base;
use app\base\service\Common;
use think\Db;

class Android extends Base
{

    public function createView()
    {
        return view('create', [
            'active' => 'create'
        ]);
    }

    public function create()
    {
        $starid = $this->req('starid', 'integer');
        $nickname = $this->req('nickname', 'require');
        $avatar = $this->req('avatar', 'require');

        $uid = User::createAndroid($nickname, $avatar);
        UserStar::joinNew($starid, $uid);

        Common::res();
    }

    public function sendHotView()
    {
        return view('sendHot', [
            'active' => 'sendHot'
        ]);
    }

    public function sendHot()
    {
        $starid = $this->req('starid', 'integer');
        $hot = $this->req('hot', 'integer');
        $limit_hot = $this->req('limit_hot', 'integer');

        $hotOffset = mt_rand(1, floor($hot * 0.1));
        $hot += $hotOffset;

        $uid = User::getOneAndroid($starid, $limit_hot);

        (new StarService())->sendHot($starid, $hot, $uid, 0, null);

        Common::res();
    }

    public function infoView()
    {
        $starid = $this->req('starid');
        $page = $this->req('page', 'integer', 1);

        $list = [];
        $totalCount = 0;
        $totalSendCount = 0;
        if ($starid) {
            $list = Db::name('user_star')->alias('s')
                ->join('user u', 'u.id = s.user_id')
                ->where('s.star_id', $starid)->where('u.type', 5)
                ->field('u.avatarurl,u.nickname,s.thisweek_count')->page($page, 10)->order('s.thisweek_count desc,s.id desc')
                ->select();
            $totalCount = Db::name('user_star')->alias('s')
                ->join('user u', 'u.id = s.user_id')
                ->where('s.star_id', $starid)->where('u.type', 5)
                ->count('s.id');
            $totalSendCount = Db::name('user_star')->alias('s')
                ->join('user u', 'u.id = s.user_id')
                ->where('s.star_id', $starid)->where('u.type', 5)
                ->sum('s.thisweek_count');
        }
        return view('info', [
            'active' => 'info',
            'list' => $list,
            'totalCount' => $totalCount,
            'totalSendCount' => $totalSendCount,
            'page' => $page,
        ]);
    }
}
