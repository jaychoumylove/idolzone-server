<?php

namespace app\api\model;

use think\Model;

class CfgShareTitle extends Model
{
    //

    public static function getOne()
    {
        $text = self::where('1=1')->orderRaw('rand()')->value('content');
        $text = str_replace('<p>', '', $text);
        $text = str_replace('</p>', '', $text);

        return $text;
    }
}
