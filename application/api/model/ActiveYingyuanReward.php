<?php

namespace app\api\model;

use app\api\service\User as UserService;
use app\base\model\Base;
use app\base\service\Common;
use Exception;
use think\Db;
use think\Model;

class ActiveYingyuanReward extends Base
{
    public static function getYingyuanReward($index,$uid){

        $info = Cfg::getCfg (Cfg::ACTIVE_YINGYUAN);
        $reward = $info['progress_reward'];
        $endReward = $reward[bcsub (count ($reward), 1)];

        if(!$index || $index>count($reward)){
            Common::res (['code' => 1, 'msg' => '数据错误']);
        }

        $userExtInfo = (new UserExt)->readMaster()->where(['user_id' => $uid])->field('yingyuan_reward,yingyuan_reward_get_num')->find();
        $sup_num = (new ActiveYingyuan)->readMaster()->where(['user_id' => $uid])->value('sup_num');
        if($sup_num-$userExtInfo['yingyuan_reward_get_num']<$reward[$index]['step'])Common::res (['code' => 1, 'msg' => '还不能领取该奖励']);

        $yingyuan_reward = $userExtInfo['yingyuan_reward'];
        $yingyuan_reward = json_decode($yingyuan_reward,true);
        if(in_array($index,$yingyuan_reward))Common::res (['code' => 1, 'msg' => '已经领取过该奖励了']);

        if(count($yingyuan_reward)>=count($reward)-2){
            $yingyuan_reward = [];
            $endRewardStep = $endReward['step'];
        }else{
            $endRewardStep = 0;
            array_push($yingyuan_reward, $index);
        }

        Db::startTrans();
        try {

            $isDone = UserExt::where(['user_id' => $uid])->update([
                'yingyuan_reward_get_num'=>Db::raw('yingyuan_reward_get_num+'.$endRewardStep),
                'yingyuan_reward'=> json_encode($yingyuan_reward),
            ]);
            if(!$isDone) Common::res (['code' => 1, 'msg' => '网络错误']);
            self::create([
                'user_id' => $uid,
                'index' => $index,
                'num' => $reward[$index]['step'],
                'reward' => json_encode($reward[$index]['reward']),
            ]);

            $desc = '领取';
            if(array_key_exists('prop',$reward[$index]['reward'])){
                $prop_text = Prop::where('id',$reward[$index]['reward']['prop'])->value('name');
                $desc .= $prop_text;
                UserProp::addProp($uid, $reward[$index]['reward']['prop'], 1);
            }else{
                $prop_text = '';
                $map = [
                    'stone' => '砖石',
                    'coin' => '金豆',
                    'panacea' => '灵丹',
                ];
                $key = array_keys($reward[$index]['reward'])[0];
                $desc .= $map[$key] . '*' . $reward[$index]['reward'][$key];
                (new UserService)->change($uid, $reward[$index]['reward'], '应援打卡奖励');
            }

            $log = [
                'user_id' => $uid,
                'item' => $reward[$index],
                'desc' => $desc,
            ];
            RecActiveYingyuan::create ($log);

            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            Common::res(['code' => 400, 'data' => $e->getMessage()]);
        }
        $data= $reward[$index]['reward'];
        $data['prop_text'] = $prop_text;

        return $data;

    }
}
