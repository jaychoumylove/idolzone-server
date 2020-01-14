<?php

namespace app\api\model;

use app\base\model\Base;

class LarenStar extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    public static function returnAixin()
    {
        
    }
}
