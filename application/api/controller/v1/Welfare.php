<?php


namespace app\api\controller\v1;


use app\api\model\CfgWelfare;
use app\api\model\UserStar;
use app\api\model\WelfareUser;
use app\base\service\Common;

class Welfare extends \app\base\controller\Base
{
    public function info()
    {
        $welfare = $this->getWelfare ();
        if (is_object ($welfare)) $welfare = $welfare->toArray ();

        $this->getUser ();

        $starId = UserStar::getStarId ($this->uid);

        $starWelfare = \app\api\model\Welfare::where('star_id', $starId)
            ->where('welfare', $welfare['id'])
            ->find ();

        $progress = $welfare['extra']['progress'];
        $lastStep = 0;
        foreach ($progress as $key => $value) {
            $value['percent'] = 0;
            if ((int)$starWelfare['count'] > (int)$value['step']) {
                $value['percent'] = 100;
            } else {
                $progressDiff = bcsub ($value['step'], $lastStep);
                $countDiff = bcsub ($starWelfare['count'], $lastStep);
                if ($countDiff > 0) $value['percent'] = bcmul (bcdiv ($countDiff, $progressDiff, 2), 100);
            }

            $lastStep = $value['step'];

            $progress[$key] = $value;
        }

        $welfare['extra']['progress'] = $progress;

        Common::res ([
            'data' => [
                'welfare' => $welfare,
                'star' => empty($starWelfare) ? null: $starWelfare,
            ]
        ]);
    }

    protected function getWelfare()
    {
        $type = input ('type', false);
        if (empty($type)) {
            Common::res (['code' => 1, 'msg' => '请选择活动']);
        }

        $welfare = CfgWelfare::where('type', $type)
            ->order ([
                'create_time' => 'desc',
                'id' => 'desc',
            ])
            ->find ();

        if (empty($welfare)) {
            Common::res (['msg' => '活动暂未开始哦', 'code' => 1]);
        }

        return $welfare;
    }

    public function rank()
    {
        $page = input ('page', 1);
        $size = input ('size', 10);
        $welfare = $this->getWelfare ();
        $this->getUser ();

        $starId = UserStar::getStarId ($this->uid);

        $starWelfare = \app\api\model\Welfare::where('star_id', $starId)
            ->where('welfare', $welfare['id'])
            ->find ();

        if (empty($starWelfare)) {
            Common::res ();
        }

        $list = WelfareUser::with(['user'])
            ->where('welfare', $starWelfare['id'])
            ->order ([
                'count' => 'desc',
                'id' => 'asc'
            ])
            ->page ($page, $size)
            ->select ();

        Common::res (['data' => $list]);
    }
}