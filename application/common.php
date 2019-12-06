<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function test($a)
{
    echo a;

    echo input('a');
}

function get_onlineip()
{
    $my_curl = curl_init();

    curl_setopt($my_curl, CURLOPT_URL, "ns1.dnspod.net:6666");

    curl_setopt($my_curl, CURLOPT_RETURNTRANSFER, 1);

    $ip = curl_exec($my_curl);

    curl_close($my_curl);

    return $ip;
}

function formatNumber($number)
{
    if (empty($number) || !is_numeric($number)) return $number;
    $unit = "";
    if ($number > 100000000) {
        $leftNumber = floor($number / 100000000);
        $rightNumber = bcmul(($number % 100000000) / 100000000, '1', 2);
        $number = floatval($leftNumber + $rightNumber);
        $unit = "亿";        
    }
    elseif ($number > 10000) {
        $leftNumber = floor($number / 10000);
        $rightNumber = round(($number % 10000) / 10000, 2);
        // $rightNumber = bcmul(($number % 10000) / 10000, '1', 2);
        $number = floatval($leftNumber + $rightNumber);
        $unit = "万";
    } else {
        $decimals = $number > 1 ? 2 : 6;
        $number = (float)number_format($number, $decimals, '.', '');
    }
    return (string)$number . $unit;
}


