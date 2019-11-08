<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\base\service\Common;
use app\api\model\UserStar;

class UserRank extends Base
{
    public function getRank()
    {
        $starid = input('starid', 0);
        $field = $this->req('field', 'require', 'thisweek_count');
        $open_id = $this->req('open_id', 'integer', 0); // 开屏图id
        $page = input('page', 1);
        $size = input('size', 10);

        $res['list'] = UserStar::getRank($starid, $field, $page, $size, $open_id);
        $this->getUser();
        $res['my'] = UserStar::getMyRankInfo($this->uid, $starid, $field);
        Common::res(['data' => $res]);
    }
}
