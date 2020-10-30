<?php


namespace app\api\model;


use app\base\model\Base;

class UserManorLog extends Base
{
    public function otherUser()
    {
        return $this->hasOne('User', 'id', 'other')->field('id,avatarurl,nickname');
    }

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    // 升级
    // 召唤
    // 灵丹获取
    public function setDataAttr($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }

    public function getDataAttr($value)
    {
        return json_decode($value, true);
    }

    public static function record($user_id, $data, $content, $type, $other = null)
    {
        self::create(compact('user_id', 'data', 'content', 'type', 'other'));
    }

    public static function recordTwelveLvUp($user_id, $animal, $lv, $number)
    {
        $type    = 'LV_UP';
        $data    = [
            'animal_id' => $animal['id'],
            'image'     => $animal['scrap_img'],
            'number'    => -abs($number),
        ];
        $content = sprintf('升级%s至Lv%s', $animal['name'], $lv);

        self::record($user_id, $data, $content, $type);
    }

    public static function recordSecret($user_id, $animal, $lv, $number, $unlock = false)
    {
        $data = [
            'animal_id' => $animal['id'],
            'image'     => $animal['scrap_img'],
            'number'    => -abs($number),
        ];
        if ($unlock) {
            $type    = 'UNLOCK';
            $content = sprintf('解锁%s', $animal['name']);
        } else {
            $type    = 'LV_UP';
            $content = sprintf('升级%s至Lv%s', $animal['name'], $lv);
        }

        self::record($user_id, $data, $content, $type);
    }

    public static function recordLottery($user_id, $animal, $number)
    {
        $type    = 'ANIMAL_LOTTERY';
        $content = sprintf('获得%s', $animal['scrap_name']);
        $data    = [
            'animal_id' => $animal['id'],
            'image'     => $animal['scrap_img'],
            'number'    => abs($number)
        ];

        self::record($user_id, $data, $content, $type);
    }

    public static function recordPanacea($user_id, $panacea, $content)
    {
        $type = $panacea > 0 ? 'INCOME_PANACEA' : 'SPEND_PANACEA';
        $data = [
            'panacea' => $panacea,
            'image'   => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fic6VmPQYib2ktqATmSxJmUtVH7OoNPjuMs2xwl26pXQGbQn74vvibp5mUNuJk7ucxzdXGAd8OlHJDA/0',
            'number'  => $panacea > 0 ? abs($panacea) : -abs($panacea)
        ];
        self::record($user_id, $data, $content, $type);
    }

    public static function recordWithAnimalBoxLottery($user, $targetUser, $animal)
    {
        $type = 'LOTTERY_ANIMAL_BOX';

        $number = 1;

        $data = [
            'animal_id' => $animal['id'],
            'image'     => $animal['scrap_img'],
        ];
        $data['number'] = $number;
        $content = sprintf('抽取了%s的%s个%s', $targetUser['nickname'], $number, $animal['scrap_name']);
        self::record($user['id'], $data, $content, $type, $targetUser['id']);
    }

    public static function recordWithNationalDay($user_id, $data, $content)
    {
        if (array_key_exists('panacea', $data)) {
            self::recordPanacea($user_id, (int)$data['panacea'], $content);
        }
        if (array_key_exists('lucky', $data)) {
            $recordData = [
                'lucky' => $data['lucky'],
                'number' => (int)$data['lucky'],
                'image' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0',
                'fixed' => 1,
            ];
            self::record($user_id, $recordData, $content, "NATIONAL_REWARD");
        }
    }

    public static function recordWithDoubleElvenDay($user_id, $data, $content)
    {
        $recordData = [
            'lucky' => (int)$data['lucky'],
            'number' => (int)$data['lucky'],
            'image' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0'
        ];
        self::record($user_id, $recordData, $content, "DOUBLE_ELVEN_REWARD");
    }

    public static function recordWithNationalDayExchangeAnimal($user_id, $animal, $item)
    {
        $type    = 'ANIMAL_EXCHANGE';
        $content = sprintf('使用%s 张幸运抽奖券兑换', $item['lucky_num']);
        $data    = [
            'animal_id' => $animal['id'],
            'image'     => $animal['scrap_img'],
            'number'    => abs($item['number'])
        ];

        self::record($user_id, $data, $content, $type);
    }

    public static function recordWithExchangeScrapByLucky($user_id, $spend, $number)
    {
        $type    = 'SCRAP_EXCHANGE';
        $content = sprintf('使用%s张幸运抽奖券兑换', $spend);
        $recordData = [
            'lucky' => (int)$number,
            'spend' => (int)$spend,
            'number' => (int)$number,
            'image' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0'
        ];

        self::record($user_id, $recordData, $content, $type);
    }
}