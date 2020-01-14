<?php
namespace app\api\controller\v1;

use app\api\model\ActiveLaren;
use app\api\model\LarenStar;
use app\api\model\LarenUser;
use app\api\model\UserExt;
use app\base\controller\Base;
use app\base\service\Common;

class Active extends Base
{
    public function laren()
    {
        $this->getUser();

        // 我的爱心
        $res['myAixin'] = UserExt::where('user_id', $this->uid)->value('aixin');
        // 列表
        $res['list'] = ActiveLaren::getList($this->uid);
        // rank
        $res['rank'] = LarenUser::rank();

        $res['noticeId'] = 26;

        Common::res(['data' => $res]);
    }

    public function sendAixin()
    {
        $this->getUser();
        $active_id = $this->req('active_id', 'integer');
        $count = $this->req('count', 'integer');

        LarenUser::send($this->uid, $active_id, $count);
        Common::res();
    }

    /**返还爱心 */
    public function returnAixin()
    {
        $activeCollection = ActiveLaren::all();

        foreach ($activeCollection as $value) {
            // TODO
            LarenStar::where('active_id', $value['id'])->order('count desc')->value('star_id');
        }
    }
}
