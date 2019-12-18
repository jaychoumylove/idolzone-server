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
        $list = self::where('btype', $btype)->field('stype')->group('stype')->select();

        foreach ($list as $key=>$value) {
            $slist = self::where('stype', $value['stype'])->select();
            $data[$value['stype']] = $slist;
            $complete = BadgeUser::getUserComplete($uid, $value['stype']);
            
            foreach ($slist as $k=>$v) {
                
                if (in_array($v['id'], $myBadgeUse)) {
                    $data[$value['stype']][$k]['status'] = 1;
                    $data[$value['stype']][$k]['create_time_has'] = BadgeUser::where(['uid'=>$uid,'badge_id'=>$v['id']])->value('left(create_time, 11) as create_time');
                }
                elseif (in_array($v['id'], $myBadgeHas)) {
                    $data[$value['stype']][$k]['status'] = 0;
                    $data[$value['stype']][$k]['create_time_has'] = BadgeUser::where(['uid'=>$uid,'badge_id'=>$v['id']])->value('left(create_time, 11) as create_time');
                }
                else{
                    $data[$value['stype']][$k]['status'] = -1;
                    if($data[$value['stype']][$k]['count']==0) $data[$value['stype']][$k]['percent'] = 0;
                    else $data[$value['stype']][$k]['percent'] = $complete/$data[$value['stype']][$k]['count']*100;
                    
                }
            }
        }
        // 正在佩戴的徽章
        $res['curBadge'] = BadgeUser::getUse($uid);
        $res['list'] = $data;
        return $res;
    }
}
