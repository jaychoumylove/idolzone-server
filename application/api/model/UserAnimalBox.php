<?php


namespace app\api\model;


use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use think\Exception;
use Throwable;

class UserAnimalBox extends Base
{

    public static function lottery($uid, $target_user)
    {
        $manor = UserManor::get(['user_id' => $uid]);
        $dayLotteryBox = $manor['day_lottery_box'];
        if (in_array($target_user, $dayLotteryBox)) {
            Common::res(['code' => 1, 'msg' => '您已经抽取过了']);
        }

        if (count($dayLotteryBox) >= 3) {
            Common::res(['code' => 1, 'msg' => '已经没有抽取次数了']);
        }

        $map    = ['user_id' => $uid, 'friend_id' => $target_user];
        $exist1 = self::get($map);
        if (empty($exist1)) {
            $exist2 = self::get(array_reverse($map));
            if (empty($exist2)) {
                Common::res(['code' => 1, 'msg' => '你不是Ta的好友哦']);
            }
        }

        $now  = time();
        $date = date('Y-m-d H:i:s', $now);

        $max = UserAnimalBox::where('user_id', $target_user)
            ->where('end_time', '>', $date)
            ->where('lottery_user', 'null')
            ->where('lottery_time', 'null')
            ->count();
        if (empty($max)) {
            Common::res(['code' => 1, 'msg' => '宝箱内没有碎片了']);
        }

        $random = $max > 1 ? rand(0, $max - 1): 0;

        $data = UserAnimalBox::where('user_id', $target_user)
            ->where('end_time', '>', $date)
            ->where('lottery_user', 'null')
            ->where('lottery_time', 'null')
            ->limit($random, 1)
            ->select();
        if (is_object($data)) $data = $data->toArray();
        $item = array_shift($data);

        $userAnimal = UserAnimal::get([
            'user_id'   => $uid,
            'animal_id' => $item['animal_id']
        ]);

        Db::startTrans();
        try {
            $updated = UserAnimalBox::where('id', $item['id'])
                ->where('lottery_user', 'null')
                ->where('lottery_time', 'null')
                ->update([
                    'lottery_user' => $uid,
                    'lottery_time' => $date
                ]);

            if (empty($updated)) {
                throw new Exception('偷取失败了，请稍后再试');
            }

            if (empty($userAnimal)) {
                UserAnimal::create([
                    'user_id'   => $uid,
                    'animal_id' => $item['animal_id'],
                    'scrap'     => 1,
                ]);
            } else {
                $updated = UserAnimal::where('user_id', $uid)
                    ->where('animal_id', $item['animal_id'])
                    ->update([
                        'scrap' => bcadd($userAnimal['scrap'], 1)
                    ]);

                if (empty($updated)) {
                    throw new Exception('偷取失败了，请稍后再试');
                }
            }
            $user = User::get($uid);
            $targetUser = User::get($target_user);
            UserManorLog::recordWithAnimalBoxLottery($user, $targetUser, $item);

            array_push($dayLotteryBox, $target_user);
            UserManor::where('id', $manor['id'])->update([
                'day_lottery_box' => json_encode($dayLotteryBox)
            ]);

            Db::commit();
        } catch (Throwable $throwable) {
            Db::rollback();
            throw $throwable;
            Common::res(['code' => 1, 'msg' => '偷取失败了，请稍后再试']);
        }

        return ['name' => $item['scrap_name'], 'number' => 1];
    }

    public static function addScrap($animal, $user_id, $endTime = null)
    {
        if (empty($endTime)) {
            $endTime = date('Y-m-d H:i:s', strtotime('+1days'));
        }

        UserAnimalBox::create([
            'user_id'    => $user_id,
            'animal_id'  => $animal['id'],
            'scrap_img'  => $animal['scrap_img'],
            'scrap_name' => $animal['scrap_name'],
            'end_time'   => $endTime,
        ]);
    }
}