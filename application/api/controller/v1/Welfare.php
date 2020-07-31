<?php


namespace app\api\controller\v1;


use app\api\model\CfgWelfare;
use app\api\model\UserStar;
use app\base\service\Common;

class Welfare extends \app\base\controller\Base
{
    public function info()
    {
        $this->getUser ();

        $welfare = CfgWelfare::order([
            'create_time' => 'desc',
            'id' => 'desc'
        ])->find ();

        if (empty($welfare)) {
            Common::res (['msg' => '活动暂未开始哦', 'code' => 1]);
        }

        $starId = UserStar::getStarId ($this->uid);

        $starWelfare = \app\api\model\Welfare::where('star_id', $starId)
            ->where('welfare', $welfare['id'])
            ->find ();

        Common::res ([
            'data' => [
                'welfare' => $welfare,
                'star' => empty($starWelfare) ? null: $starWelfare,
            ]
        ]);
    }
}