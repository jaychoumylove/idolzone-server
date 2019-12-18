<?php

namespace app\api\controller\v1;

use app\api\model\CfgBadge;
use app\api\model\BadgeUser;
use app\base\controller\Base;
use app\base\service\Common;

class Badge extends Base
{
    public function achieve()
    {
        $this->getUser();
        $res = BadgeUser::with('CfgBadge')
            ->where(['uid'=>$this->uid, 'status'=>0])
            ->where('create_time=update_time')
            ->order('id desc')
            ->field('badge_id,left(create_time, 11) as create_time')
            ->find();
        BadgeUser::where(['uid'=>$this->uid, 'status'=>0])->where('create_time=update_time')->update(['update_time'=>date('Y-m-d H:i:s')]);
        Common::res(['data' => $res]);        
    }
    
    public function select()
    {
        $btype = input('btype',0);
        $this->getUser();
        $res = CfgBadge::getAll($this->uid, $btype);
        Common::res(['data' => $res]);
    }
    
    public function use()
    {
        $this->getUser();
        $stype = input('stype',0);
        $badge_id = input('badgeId',0);
        BadgeUser::use($this->uid, $stype, $badge_id);
        Common::res();
    }
    public function cancel()
    {
        $this->getUser();
        $badge_id = input('badgeId');
        BadgeUser::cancel($this->uid, $badge_id);
        Common::res();
    }
}
