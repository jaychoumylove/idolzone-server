<?php

namespace app\api\model;

use app\base\model\Base;
use think\Db;

class StarRank extends Base
{
    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    public static function getRankList($page, $size, $rankField, $keywords = '')
    {
        // 关键字
        if ($keywords !== '') {
            $ids = Star::where('name', 'like', '%' . $keywords . '%')->column('id');
            $w = ['star_id' => ['in', $ids]];
        } else {
            $w = '1=1';
        }

        // 查询字段
        if ($rankField == 'last_week_hot') {
            $list = json_decode(StarRankHistory::where('field', 'week_hot')->order('id desc')->value('value'), true);
        } else if ($rankField == 'last_month_hot_flower') {
            $list = json_decode(StarRankHistory::where('field', 'month_hot_flower')->order('id desc')->value('value'), true);
        } else if ($rankField == 'last_month_hot_coin') {
            $list = json_decode(StarRankHistory::where('field', 'month_hot_coin')->order('id desc')->value('value'), true);
        } else if ($rankField == 'tiegan') {
            // 铁杆粉丝榜

        } else if ($rankField == 'fengyun') {
            // 占领封面榜 （风云榜）
            // 小时贡献榜
            $list = RecHour::getRankList($page, $size);
        } else {
            // 本周本月的记录
            $list = self::with('star')->where($w)->field('*,' . $rankField . ' as hot')->order($rankField . ' desc,id asc')
                ->page($page, $size)->select();
        }

        return $list;
    }

    /**明星人气增加 */
    public static function change($starid, $hot, $type)
    {
        $update = [
            'week_hot' => Db::raw('week_hot+' . $hot),
            'month_hot' => Db::raw('month_hot+' . $hot),
        ];
        if ($type == 1) {
            $extraHot = 'month_hot_coin';
            $update[$extraHot] = Db::raw($extraHot . '+' . $hot);
        } else if ($type == 2) {
            $extraHot = 'month_hot_flower';
            $update[$extraHot] = Db::raw($extraHot . '+' . $hot);
        } 

        self::where('star_id', $starid)->update($update);
    }
}
