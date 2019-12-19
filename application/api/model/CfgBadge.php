<?php

namespace app\api\model;

use app\base\model\Base;
use app\api\model\BadgeUser;
use think\Model;

class CfgBadge extends Base
{
    public static function getAll($uid,$btype)
    {
        $myBadgeHas = BadgeUser::where('uid', $uid)->column('badge_id');
        $myBadgeUse = BadgeUser::where('uid', $uid)->where('status', 1)->column('badge_id');
        $list = self::where('btype', $btype)->order('stype asc,id asc')->select();

        foreach ($list as $key=>&$value) {
            if (in_array($value['id'], $myBadgeUse)) {
                $value['status'] = 1;
                $value['create_time_has'] = BadgeUser::where(['uid'=>$uid,'badge_id'=>$value['id']])->value('left(create_time, 11) as create_time');
            }
            elseif (in_array($value['id'], $myBadgeHas)) {
                $value['status'] = 0;
                $value['create_time_has'] = BadgeUser::where(['uid'=>$uid,'badge_id'=>$value['id']])->value('left(create_time, 11) as create_time');
            }
            else{
                if(!isset($complete[$value['stype']])) $complete[$value['stype']] = BadgeUser::getUserComplete($uid, $value['stype']);
                $value['status'] = -1;
                if($value['count']==0) $value['percent'] = 0;
                else $value['percent'] = $complete[$value['stype']]/$value['count']*100;
            }
            
            $data[$value['stype']][] = $value;
        }
        // 正在佩戴的徽章
        $res['curBadge'] = BadgeUser::getUse($uid);
        $res['list'] = $data;
        return $res;
    }
}
