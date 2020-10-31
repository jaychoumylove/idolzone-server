<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;

class CfgForbiddenWords extends Base
{
    public static function noAds($content){
        $forbiddenWords = self::column('preg');
        //禁止违规词
        foreach ($forbiddenWords as $preg){
            if(preg_match("/$preg/is", $content))  return false;
        }
        return true;
    }
}
