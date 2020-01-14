<?php

namespace app\api\model;

use app\base\model\Base;
use think\Model;

class Hotsearch extends Base
{
    /**推送 */
    public static function saveAndPush($data)
    {
        $isDone = self::where('content', $data['content'])->update([
            'rank' => $data['rank'],
            'update_time' => date('Y-m-d H:i:s')
        ]);
        if (!$isDone) {
            self::create([
                'rank' => $data['rank'],
                'content' => $data['content'],
                'star_id' => $data['star_id'],
            ]);

            // 推送

            
        }
    }
}
