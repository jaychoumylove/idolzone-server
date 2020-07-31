<?php


namespace app\api\model;


use think\Db;

class CfgWelfare extends \app\base\model\Base
{
    const STONE_WELFARE = 'STONE_WELFARE';

    public static function setWelfare($user_id, $type, $number)
    {
        $welfare = self::getNormalWelfare ($type);
        if (empty($welfare)) {
            return false;
        }

        Db::startTrans ();
        try {
            $star_id = UserStar::getStarId ($user_id);

            $starWelfareMap = [
                'star_id' => $star_id,
                'welfare' => $welfare['id'],
            ];
            $starWelfare = Welfare::get($starWelfareMap);

            if (empty($starWelfare)) {
                $starWelfareCreate = ['count' => $number];
                $starWelfare = Welfare::create (array_merge ($starWelfareCreate, $starWelfareMap));
            } else {
                $starWelfareUpdate = ['count' => bcadd ($number, $starWelfare['count'])];
                $starWelfareUpdated = Welfare::where('id', $starWelfare['id'])->update($starWelfareUpdate);
                if (empty($starWelfareUpdated)) {
                    throw new \Exception('更新失败');
                }
            }

            $userWelfareMap = [
                'user_id' => $user_id,
                'welfare' => $starWelfare['id'],
            ];
            $userWelfare = WelfareUser::get ($userWelfareMap);
            if (empty($userWelfare)) {
                $userWelfareCreate = ['count' => $number];
                WelfareUser::create (array_merge ($userWelfareCreate, $userWelfareMap));
            } else {
                $userWelfareUpdate = ['count' => bcadd ($number, $userWelfare['count'])];
                $userWelfareUpdated = WelfareUser::where('id', $userWelfare['id'])->update($userWelfareUpdate);
                if (empty($userWelfareUpdated)) {
                    throw new \Exception('更新失败');
                }
            }

            Db::commit ();
        }catch (\Throwable $throwable) {
            Db::rollback ();
        }
    }

    public static function getNormalWelfare($type)
    {
        $currentTime = time ();

        return self::where('end', '>', $currentTime)
            ->where('type', $type)
            ->order ([
                'create_time' => 'desc',
                'id' => 'desc',
            ])
            ->find ();
    }
}