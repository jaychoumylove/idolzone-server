<?php


namespace app\api\model;


class RecLuckyDrawLog extends \app\base\model\Base
{
    const SCRAP_L = 'SCRAP_L'; // 碎片成品
    const SINGLE = 'SINGLE'; // 碎片成品
    const MULTIPLE = 'MULTIPLE'; // 碎片成品Multiply

    public static function getLogPager($user_id, $page, $size)
    {
        $count = RecLuckyDrawLog::where('user_id', $user_id)->count ();

        $list = RecLuckyDrawLog::where('user_id', $user_id)
            ->order ([
                'create_time' => "desc",
                'id' => 'desc'
            ])
            ->page ($page, $size)
            ->select ();
        if (is_object ($list)) $list = $list->toArray ();

        $newList = [];
        foreach ($list as $key => $value)
        {
            if ($value['type'] == self::SINGLE) {
                $item = self::supportItemByType ($value['item']);
            }
            if ($value['type'] == self::MULTIPLE) {
                $item = $value['item'];

                foreach ($item as $ind => $ite) {
                    $item[$ind] = self::supportItemByType ($ite);
                }
            }

            $value['item'] = $item;
            array_push ($newList, $value);
        }

        $data['list'] = $newList;
        $data['count'] = $count;
    }

    protected static function supportItemByType($item)
    {
        if ($item['type'] == CfgLuckyDraw::SCRAP)
        {
            $item['image'] = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0';
        }
        if ($item['type'] == RecLuckyDrawLog::SCRAP_L)
        {
            $scrap = CfgScrap::get ($value['item']['key']);
            $item['image'] = $scrap['image_l'];
        }

        $currencyMap = [
            'coin' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0',
            'flower' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0',
            'stone' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0',
            'trumpet' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk90Xa3b43zE46M8IkUvFyMR5GgfJN52icBqoicfKWfAJS8QXog0PZtgdEQ/0',
        ];
        if (array_key_exists ($item['key'], $currencyMap)) {
            $item['image'] = $currencyMap[$item['key']];
        }

        return $item;
    }

    public function user()
    {
        return $this->hasOne ('User', 'id', 'user_id')->field('id,avatarurl,nickname');
    }

    public function setItemAttr($value)
    {
        if (is_array ($value)) $value = json_encode ($value);

        return $value;
    }

    public function getItemAttr($value)
    {
        return json_decode ($value, true);
    }
}