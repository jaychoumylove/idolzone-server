<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;

class Article extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'submit_user_id', 'id')->field('id,nickname,avatarurl');
    }

    /**文章列表 */
    public static function getList($starid, $page, $size)
    {
        $res = self::with('user')->where('star_id', $starid)->order('join_time desc,id desc')->page($page, $size)->select();

        $res = json_decode(json_encode($res, JSON_UNESCAPED_UNICODE), true);
        foreach ($res as &$art) {
            // imgs json
            $art['imgs'] = json_decode($art['imgs']);
            // tips
            // 时间
            $spaceTime = time() - strtotime($art['join_time']);
            if ($spaceTime < 3600) {
                // 一个小时之内的
                $art['tips'] = floor($spaceTime / 60) . '分钟之前';
            } else if ($spaceTime < 24 * 3600) {
                // 一天之内的
                $art['tips'] = floor($spaceTime / 3600) . '小时之前';
            } else {
                $art['tips'] = substr($art['join_time'], 5, 11);
            }
            // 来源
            if ($art['weibo_id']) {
                $art['tips'] .= ' 来自 微博';
            }

            // 转发
            $exp = explode('|zf|', $art['title']);
            if (count($exp) > 1) {
                $art['transmit_title'] = $exp[0];
                $art['title'] = $exp[1];
            }
        }
        return $res;
    }
}
