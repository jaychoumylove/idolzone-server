<?php


namespace app\api\controller;


use app\api\model\CfgAnimal;
use app\api\model\Rec;
use app\api\model\UserAnimal;
use app\api\model\UserCurrency;
use app\api\model\UserExt;
use app\api\model\UserManor;
use app\api\model\UserSprite;
use app\api\service\User;
use app\base\controller\Base;
use think\Db;
use think\Log;
use think\Response;

class Fix extends Base
{
    public function manorFix()
    {
        // 每一位庄园主加1小时庄园产量
        $page = input('page', 1);
        $manorList = UserManor::order([
            'output' => 'desc'
        ])->page($page, 1000)
            ->select();
        if (is_object($manorList)) $manorList = $manorList->toArray();

        foreach ($manorList as $item) {
            $output = (int)UserAnimal::getOutput($item['user_id'], CfgAnimal::OUTPUT);
            $hour = $output*60*60*2/10;

            UserManor::where('user_id', $item['user_id'])->update([
                'day_count' => Db::raw('day_count+'.$hour),
                'active_sum' => Db::raw('active_sum+'.$hour),
                'week_count' => Db::raw('week_count+'.$hour),
            ]);

            (new User())->change($item['user_id'], ['coin' => $hour], '庄园金豆补偿');
        }
        return Response::create(['status' => 'OK','page' => $page], 'json');
    }

    public function farmFix()
    {
        // 每一位庄园主加1小时庄园产量
        $page = input('page', 1);
        $farmList = UserSprite::order([
            'total_speed_coin' => 'desc'
        ])->page($page, 1000)
            ->select();
        if (is_object($farmList)) $farmList = $farmList->toArray();

        foreach ($farmList as $item) {
            $speed = $item['total_speed_coin'];
            $hour = $speed*60*60*2/100;

            (new User())->change($item['user_id'], ['coin' => $hour], '农场金豆补偿');
        }
        return Response::create(['status' => 'OK','page' => $page], 'json');
    }

    public function PkFix()
    {
        $pkUser = Db::name('pk_user_copy')->where('pk_time', '2020-09-25 08:00:00')
            ->select();
        // 0 钻石场 1 鲜花场
        if (is_object($pkUser)) $pkUser = $pkUser->toArray();

        $stoneUser = array_filter($pkUser, function($item) {
            return $item['pk_type'] < 1;
        });
        $flowerUser = array_filter($pkUser, function($item) {
            return $item['pk_type'] > 0;
        });

        foreach ($stoneUser as $item) {
            (new User())->change($item['uid'], ['stone' => 5], 'PK奖励补偿');
        }
        foreach ($flowerUser as $item) {
            (new User())->change($item['uid'], ['flower' => 10000], 'PK奖励补偿');
        }
        return Response::create(['status' => 'OK'], 'json');
    }

    public function removeDict()
    {
        $res = Db::query("select user_id,coin,before_coin,id from f_rec where content = '农场金豆补偿' and delete_time is not null order by user_id;");

//        echo count($res).PHP_EOL;die();
        $dealUser = [];
        $repeatUser = [];
        foreach ($res as $item) {
            $userId = $item['user_id'];
            $item = Rec::where('user_id', $userId)
                ->where('content', '农场金豆补偿')
                ->find();
            $coins = Rec::where('id', '>', $item['id'])
                ->where('user_id', $userId)
                ->column('coin');
            $all = array_sum($coins);
            if (in_array($userId, $dealUser)) {
                echo 'user_id : '.$userId.PHP_EOL;
                if (in_array($userId, $repeatUser) == false) {
                    array_push($repeatUser, $userId);
                }
//                Log::error(json_encode($item));
//                $coin = bcadd($item['before_coin'], $item['coin']);
//                Db::raw('if(`mass_time` = '.$massDate.', 1, 0)'),
//                UserCurrency::where('uid', $userId)->update(['coin' => Db::raw('if(`coin` > '.$coin.', coin-'.$coin.', 0)')]);
            } else {
//                Log::error(json_encode($userId));
//                array_push($dealUser, $userId);
//                $coin = bcadd($item['before_coin'], $item['coin']);
//                UserCurrency::where('uid', $userId)->update(['coin' => Db::raw('coin+'.$coin)]);
//                Log::error(json_encode($item));
                echo 'user_id : '.$userId.PHP_EOL;
            }
        }

//        echo count($repeatUser);
//        Log::error(json_encode(['repeat' => $repeatUid,'deal' => $dealUid]));

        die('over');
    }
}