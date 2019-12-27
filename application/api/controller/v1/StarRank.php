<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank as StarRankModel;
use app\base\service\Common;
use app\api\model\StarRankHistory;
use app\api\service\Star;

class StarRank extends Base
{
    /**明星人气榜单 */
    public function getRankList()
    {
        $page = input('page', 1);
        $size = input('size', 10);
        $keywords = input('keywords', '');
        $rankField = input('rankField', 'week_hot');
        if ($rankField == 'undefined') {
            $rankField = 'week_hot';
        }
        $type = input('type', 0);

        $list = StarRankModel::getRankList($page, $size, $rankField, $keywords);

        Common::res(['data' => $list]);
    }

    public function search()
    { }

    /**历史榜单 */
    public function getRankHistory()
    {
        // $page = input('page', 1);
        // $size = input('size', 10);

        $rankField = input('rankField', 'week_hot');

        $res = StarRankHistory::where(['field' => $rankField])->order('date desc')->select();

        foreach ($res as &$value) {
            $year = substr($value['date'], 0, 4);
            if ($rankField == 'week_hot') {
                $week = substr($value['date'], -2);
                // TODO:
                $value['date'] = $year . '年' . ($week - 19) . '期';
            } else if ($rankField == 'month_hot') {
                $month = substr($value['date'], -2);

                $value['date'] = $year . '年' . $month . '月';
            }
        }
        Common::res(['data' => $res]);
    }

    public function getRank()
    {
        $list = StarRankModel::with(['star'])->order('week_hot desc')->field('id,star_id')->limit(100)->select();

        foreach ($list as $value) {
            for ($i = 1; $i <= 1; $i++) {
                echo "<a href='https://m.weibo.cn/api/container/getIndex?containerid=100103"
                    . urlencode('type=60&q=' . $value['star']['name'] . '&t=0') . "&page_type=searchall&page={$i}'>{$value['star']['name']}|{$value['star_id']}</a></br>";
            }
        }
    }
}
