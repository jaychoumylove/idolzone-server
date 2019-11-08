<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank as StarRankModel;
use app\base\service\Common;
use app\api\model\StarRankHistory;
use app\api\model\UserExt;
use app\api\model\Cfg;
use app\api\model\UserSprite;
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
}
