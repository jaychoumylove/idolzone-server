<?php
namespace app\api\controller\v1;

use app\api\model\CfgTaskfather;
use app\api\model\Father as ModelFather;
use app\api\model\UserExt;
use app\api\service\Task;
use app\base\controller\Base;
use app\base\service\Common;
use app\base\service\WxAPI;

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
        $status = $this->req('status', 'integer',0);
        
        ModelFather::baishi($father_uid, $son_uid,$status);
        Common::res();
    }

    public function editMsg()
    {
        $msg = $this->req('msg', 'require');
        (new WxAPI())->msgCheck($msg);//非法词检测
        
        $this->getUser();
        UserExt::where('user_id', $this->uid)->update(['father_open_msg' => $msg]);
        Common::res();
    }

    public function editNotice()
    {
        $notice = $this->req('notice', 'require');
        (new WxAPI())->msgCheck($notice);//非法词检测
        
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
    
    public function applyList()
    {
        $this->getUser();
        $list = ModelFather::getApplyList($this->uid);
        Common::res(['data' => $list]);
    }

    public function applyDeal()
    {
        $f_id = $this->req('fid', 'integer');
        $uid = $this->req('uid', 'integer');
        $status = $this->req('status', 'integer');
        $this->getUser();
    
        ModelFather::applydeal($f_id, $uid, $status);
        Common::res();
    }
    
    public function exit()
    {
        $son_uid = $this->req('son_uid', 'integer');
        ModelFather::exit($son_uid);
        Common::res();
    }
}
