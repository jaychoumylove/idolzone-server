<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class StarRankPkactive extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    public static function getRankList($page, $size)
    {
        return self::with('star')->field('*,pkactive_hot as hot')->order('pkactive_hot desc,score desc,update_time asc')
                ->page($page, $size)->select();
    }
}
