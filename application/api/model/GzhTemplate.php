<?php

namespace app\api\model;

use app\base\model\Base;

class GzhTemplate extends Base
{
    /**
     * @param $appid 跳转的小程序
     * @param $url 跳转的url
     */
    public function getTemplate($id, $openid, $appid = '', $url = '', $data = [])
    {
        $value = self::where('id', $id)->value('value');

        $value = str_replace('{$openid}', $openid, $value);
        $value = str_replace('{$url}', $url, $value);
        $value = str_replace('{$appid}', $appid, $value);

        for ($i = 0; $i < count($data); $i++) {
            $value = str_replace('{$' . ($i + 1) . '}', $data[$i], $value);
        }

        return $value;
    }
}
