<?php


namespace app\api\model;


use app\api\service\User;
use app\base\service\Common;
use think\Db;

class RecUserCourageBox extends \app\base\model\Base
{

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,nickname,avatarurl');
    }

    public static function getReward($uid, $type, $index)
    {
        $userStarInfo = UserStar::where('user_id',$uid)->find();
        if (!$userStarInfo) Common::res(['code' => 1, 'msg' => '请先选择加入一个圈子']);
        $pet_adventure = Cfg::getCfg('pet_adventure');
        if ($type == 'box') {
            $data = $pet_adventure['box_list']['list_info'];
        } elseif ($type == 'share_box') {
            $data = $pet_adventure['share_box_list']['list_info'];
        } elseif ($type == 'share_big_box') {
            $data = $pet_adventure['share_big_box_list']['list_info'];
        } elseif ($type == 'big_box') {
            $data = $pet_adventure['big_box_list']['list_info'];
        }

        $my_courage = UserStar::where('user_id', $uid)->value('courage');
        $needCount = $data['need_courage'] + $index * $data['need_courage'];
        if($my_courage<$needCount) Common::res(['code' => 1, 'msg' => '您的勇气值不足']);
        $coin = 0;
        $panacea = 0;
        if($data['type'] == 'coin'){
            $coin = $data['number'];
        }
        if($data['type'] == 'panacea'){
            $panacea = $data['number'];
        }

        Db::startTrans();
        try {
            $isDone = self::where('user_id',$uid)->where('index',$index)->where('type',$type)->where('date',date('Y-m-d'))->update([
                'update_time' => date('Y-m-d H:i:s')
            ]);
            if ($isDone) Common::res(['code' => 1, 'msg' => '已领取']);

            self::create([
                'user_id' => $uid,
                'type' => $type,
                'index' => $index,
                'date' => date('Y-m-d'),
                'coin' => $coin,
                'panacea' => $panacea,
            ]);
            (new User)->change($uid, ['coin' => $coin, 'panacea' => $panacea], '宠物冒险宝箱奖励');

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
        return ['coin'=>$coin,'panacea'=>$panacea];
    }

    public static function getList($uid, $type)
    {
        $pet_adventure = Cfg::getCfg('pet_adventure');
        if ($type == 'box') {
            $data = $pet_adventure['box_list']['list_info'];
        } elseif ($type == 'share_box') {
            $data = $pet_adventure['share_box_list']['list_info'];
        } elseif ($type == 'share_big_box') {
            $data = $pet_adventure['share_big_box_list']['list_info'];
        } elseif ($type == 'big_box') {
            $data = $pet_adventure['big_box_list']['list_info'];
        }
        $list = [];
        $count = ($data['end_courage'] - $data['start_courage'] + $data['need_courage']) / $data['need_courage'];
        for ($i = 0; $i < $count; $i++) {
            $is_get = self::where('user_id',$uid)->where('index',$i)->where('type',$type)->where('date',date('Y-m-d'))->count();
            $list[$i] = [
                'title' => $data['title'],
                'image' => $data['image'],
                'type' => $data['type'],
                'number' => $data['number'],
                'need_courage' => $data['need_courage'] + $i * $data['need_courage'],
                'is_get' => $is_get
            ];
        }

        return $list;
    }

}