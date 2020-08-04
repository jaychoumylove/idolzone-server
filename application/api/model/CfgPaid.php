<?php


namespace app\api\model;


class CfgPaid extends \app\base\model\Base
{
    const SUM = 'SUM';
    const DAY = 'DAY';

    const DAY_FINISH = 2;
    const DAY_READY = 1;
    const DAY_EMPTY = 0;

    const ON = 'ON';
    const OFF = 'OFF';

    const CURRENCY = 'currency';
    const PROP = 'prop';

    public function getRewardAttr($value)
    {
        $rewards = json_decode ($value, true);
        $currencyMap = [
            'coin' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0',
            'flower' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0',
            'stone' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0',
            'trumpet' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk90Xa3b43zE46M8IkUvFyMR5GgfJN52icBqoicfKWfAJS8QXog0PZtgdEQ/0',
        ];

        foreach ($rewards as $key => $reward) {
            if ($reward['type'] == self::CURRENCY) {
                if (array_key_exists ($reward['key'], $currencyMap)) {
                    $reward['image'] = $currencyMap[$reward['key']];
                }
            }
            $rewards[$key] = $reward;
        }

        return $rewards;
    }

    public function setRewardAttr($value)
    {
        return json_encode ($value);
    }

    public static function getCheckType($type)
    {
        $types = self::where('status', self::ON)->column ('type');
        return in_array ($type, $types);
    }
}