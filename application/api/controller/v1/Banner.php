<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Banner as BannerModel;
use app\base\service\Common;
use app\api\model\Article;
use app\api\model\Fanclub;
use app\api\model\RecHour;

class Banner extends Base
{

    /**banner图列表 */
    public function getList()
    {
        $banner_type = input('banner_type', 0);
        $res['bannerList'] = BannerModel::all(['type' => $banner_type]);

        // 小banner
        // $artList = Article::whereTime('create_time', 'w')->field('name,"/pages/subPages/notice/list/list" as page')->select();
        // $fanclubList = Fanclub::where('status', 2)->field('concat(clubname,"已入驻") as name,"/pages/subPages/fanclub_list/fanclub_list" as page')->select();
        // $res['smallList'] = array_merge($artList, $fanclubList);

        Common::res(['data' => $res]);
    }

    public function getTop()
    {
        $res = RecHour::getTop();
        Common::res(['data' => $res]);
    }
}
