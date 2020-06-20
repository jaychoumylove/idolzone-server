<?php

namespace app\api\model;

use app\api\model\User as UserModel;
use app\base\model\Base;

class ActiveDragonBoatFestivalFanclub extends Base
{
    public static function getList($uid,$active_id,$page,$size)
    {
        $list=self::where('active_id',$active_id)->order('total_count desc,create_time asc')->page($page, $size)->select();
        $list = json_decode(json_encode($list),TRUE);
        foreach ($list as &$value){
            $star_id=Fanclub::where('id',$value['fanclub_id'])->value('star_id');
            $value['star_name']=Star::where('id',$star_id)->value('name');
        }

        return $list;
    }

    public static function getUserList($uid,$page,$size)
    {
        $fanclub_id = FanclubUser::where('user_id', $uid)->value('fanclub_id');
        $list=FanclubUser::with('user')->where('fanclub_id',$fanclub_id)->field('id,dragon_boat_festival_hot,user_id,fanclub_id')
            ->order('dragon_boat_festival_hot desc')->page($page, $size)->select();
        $list = json_decode(json_encode($list),TRUE);
        foreach ($list as &$value){
            $value['user']['headwear'] = HeadwearUser::getUse($value['user_id']);
            $value['user']['level'] = CfgUserLevel::getLevel($value['user_id']);
        }
        $result['list']=$list;

        $dragon_boat_festival_hot_members=FanclubUser::where('fanclub_id',$fanclub_id)->order('dragon_boat_festival_hot desc')->column('user_id');
        $result['myinfo']['dragon_boat_festival_hot'] = FanclubUser::where('fanclub_id',$fanclub_id)->where('user_id', $uid)->value('dragon_boat_festival_hot');
        $result['myinfo']['rank']=array_search($uid,$dragon_boat_festival_hot_members)+1;
        $result['myinfo']['headwear'] = HeadwearUser::getUse($uid);
        $result['myinfo']['level'] = CfgUserLevel::getLevel($uid);

        return $result;
    }
}
