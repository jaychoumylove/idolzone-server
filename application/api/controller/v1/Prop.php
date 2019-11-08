<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\UserProp;
use app\base\service\Common;

class Prop extends Base
{

    public function use()
    {
        $id = input('id');
        if (!$id) Common::res(['code' => 100]);

        $res = UserProp::use($id);
        Common::res(['data' => $res]);
    }
}
