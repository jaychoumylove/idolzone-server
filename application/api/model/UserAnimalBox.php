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
        $now  = time();
        $date = date('Y-m-d H:i:s', $now);

        $max = UserAnimalBox::where('user_id', $target_user)
            ->where('end_time', '>', $date)
            ->where('lottery_user', 'null')
            ->where('lottery_time', 'null')
            ->count();

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
            $nickname = User::get($uid)['nickname'];
            UserManorLog::recordWithAnimalBoxLottery($uid, $nickname, $item, true);
            $nickname = User::get($target_user)['nickname'];
            UserManorLog::recordWithAnimalBoxLottery($target_user, $nickname, $item, false);

            Db::commit();
        } catch (Throwable $throwable) {
            Db::rollback();
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