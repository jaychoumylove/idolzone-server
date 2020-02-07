<?php

namespace app\api\controller\v1;

use app\api\model\CfgTaskfather;
use app\api\model\Father as ModelFather;
use app\api\model\UserExt;
use app\api\model\UserSprite;
use app\api\service\Task;
use app\base\controller\Base;
use app\base\service\Common;

class Father extends Base
{

    public function info()
    {
        $user_id = $this->req('user_id', 'integer', 0);
        $this->getUser();
        if (!$user_id) $user_id = $this->uid;
        $res = ModelFather::type($user_id);
        Common::res(['data' => $res]);
    }

    public function fatherList()
    {
        $this->getUser();
        $res = ModelFather::getFatherList($this->uid);
        Common::res(['data' => $res]);
    }

    /**拜师 */
    public function baishi()
    {
        $father_uid = $this->req('father_uid', 'integer');
        $son_uid = $this->req('son_uid', 'integer');
        ModelFather::baishi($father_uid, $son_uid);
        Common::res();
    }

    public function editMsg()
    {
        $msg = $this->req('msg', 'require');
        $img = $this->req('img', 'require');
        $this->getUser();
        UserExt::where('user_id', $this->uid)->update(['father_open_msg' => $msg, 'father_open_img' => $img]);
        Common::res();
    }

    public function editNotice()
    {
        $notice = $this->req('notice', 'require');
        $this->getUser();
        UserExt::where('user_id', $this->uid)->update(['father_notice' => $notice]);
        Common::res();
    }

    public function taskList()
    {
        $active = $this->req('active', 'integer');
        $this->getUser();
        $list = CfgTaskfather::getList($active, $this->uid);
        Common::res(['data' => $list]);
    }

    public function taskSettle()
    {
        $task_id = $this->req('task_id', 'integer');
        $this->getUser();

        $earn = (new Task())->settleFather($task_id, $this->uid);
        Common::res(['data' => $earn]);
    }

    public function sonList()
    {
        $active = $this->req('active', 'integer');
        $this->getUser();
        $list = ModelFather::getSonList($active, $this->uid);
        Common::res(['data' => $list]);
    }

    public function exit()
    {
        $son_uid = $this->req('son_uid', 'integer');
        ModelFather::exit($son_uid);
        Common::res();
    }
}
