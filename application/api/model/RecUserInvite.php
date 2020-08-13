<?php


namespace app\api\model;


class RecUserInvite extends \app\base\model\Base
{
    public function setRewardAttr($value)
    {
        if (is_array ($value)) {
            return json_encode ($value);
        } else {
            return $value;
        }
    }

    public function getRewardAttr($value)
    {
        return json_decode ($value, true);
    }

    public static function add($id, $reward, $type = 'user')
    {
        $data = [
            'reward' => $reward
        ];
        $key = '';
        if ($type == 'star') {
            $star = Star::get($id);
            $title = sprintf ("累计拉新100人，【%s】获得周榜人气", $star['name']);
            $key = 'star_id';
        }
        if ($type == 'user') {
            $title = "拉新奖励";
            $key = 'user_id';
        }
        if (empty($key)) return false;
        if (empty($title)) return false;

        $data[$key] = $id;
        $data['title'] = $title;

        self::create ($data);

        return true;
    }
}