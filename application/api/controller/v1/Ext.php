<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\RecUserFormid;
use app\base\service\Common;
use app\api\model\CfgShareTitle;
use app\api\model\Cfg;
use app\api\model\CfgActive;
use app\api\model\UserStar;
use app\base\service\WxAPI;
use app\api\model\Rec;
use app\api\model\UserSprite;
use app\api\model\RecActive;
use app\api\model\GuideCron;
use app\api\model\GzhUserPush;
use app\api\service\User;
use app\api\model\UserExt;
use app\base\model\Appinfo;

class Ext extends Base
{

    public function saveFormId()
    {
        $formId = $this->req('formId', 'require');
        $this->getUser();

        RecUserFormid::create([
            'user_id' => $this->uid,
            'form_id' => $formId,
        ]);
        Common::res();
    }

    public function config()
    {
        $key = input('key');
        if ($key) {
            if ($key == 'open_img') {
                $open_img = GuideCron::where('start_time', '<', time())->where('end_time', '>', time())->value('open_img');
                if (!$open_img) $open_img = Cfg::getCfg($key);
                Common::res(['data' => $open_img]);
            } else {
                Common::res(['data' => Cfg::getCfg($key)]);
            }
        }

        $res = Cfg::getList();
        // 顺便获取分享信息
        $res['share_text'] = CfgShareTitle::getOne();

        Common::res(['data' => $res]);
    }

    public function activeList()
    {
        $list = CfgActive::all();
        if(input('platform')=='MP-QQ') $list = CfgActive::limit(1)->select();
        $starid = $this->req('starid', 'integer');

        foreach ($list as &$value) {
            // 离活动结束还剩
            $value['active_end'] = strtotime(json_decode($value['active_date'], true)[1]) - time();
            $value['progress'] = RecActive::getProgress($starid, $value['id'], $value['min_days']);
        }

        Common::res(['data' => $list]);
    }

    /**活动信息 */
    public function getActiveInfo()
    {
        $starid = $this->req('starid', 'integer');
        $active_id = $this->req('id', 'integer', 0);
        if (!$active_id) {
            $active_id = CfgActive::where('1=1')->value('id');
        }
        $this->getUser();

        $res = UserStar::getActiveInfo($this->uid, $starid, $active_id);

        Common::res(['data' => $res]);
    }

    /**活动 打卡 */
    public function setCard()
    {
        $this->getUser();
        $starid = $this->req('starid', 'integer');
        $active_id = $this->req('active_id', 'integer');
        if (!$active_id) {
            $active_id = CfgActive::where('1=1')->value('id');
        }

        UserStar::setCard($this->uid, $starid, $active_id);
        Common::res();
    }

    public function userRank()
    {
        $starid =  input('starid');
        $page =  input('page', 1);
        $size =  input('size', 10);
        $active_id = $this->req('active_id', 'integer');
        if (!$active_id) {
            $active_id = CfgActive::where('1=1')->value('id');
        }

        $list = RecActive::with(['user'])->where('star_id', $starid)->where('total_clocks', '>', 0)->where('active_id', $active_id)
            ->order('total_clocks desc')->page($page, $size)->select();

        Common::res(['data' => $list]);
    }

    public function uploadIndex()
    {
        return view('upload');
    }

    /**文件上传 */
    public function upload()
    {
        $file_url = input('url', '');
        if ($file_url) {
            // 上传的url
            $content = file_get_contents($file_url);
            $file_arr = explode('.', $file_url);
            // 文件名及扩展名
            $filename = time() . mt_rand(1000, 9999) . '.' . $file_arr[count($file_arr) - 1];
            $realPath = ROOT_PATH . 'public' . DS . 'uploads' . DS . $filename;
            file_put_contents($realPath, $content);
        } else {
            // 上传的文件
            $file = request()->file('file');
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $filename = $info->getSaveName();
                $realPath = ROOT_PATH . 'public' . DS . 'uploads' . DS . $filename;
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        if ($realPath) {
            //违规检测
            (new WxAPI())->imgCheck($realPath);
            
            // 上传到微信
            $gzh_appid = Appinfo::where(['type' => 'gzh','status'=>0])->value('appid');
            if(!$gzh_appid) Common::res(['code' => 1, 'msg' => '图片服务器不可用，请联系客服']);          
            
            $res = (new WxAPI($gzh_appid))->uploadimg($realPath);
            if (isset($res['errcode']) && $res['errcode'] == 45009){//公众号达到日极限
                Appinfo::where(['appid' => $gzh_appid])->update(['status'=>-1]);
                Common::res(['code' => 1, 'msg' => '上传失败，请重试一次']);
            }
            
            //获取到地址才返回
            if(isset($res['url'])){
                
                $res['https_url'] = str_replace('http', 'https', $res['url']);
                unlink($realPath);
                Common::res(['data' => $res]);
                
            }
            
            if (isset($res['errcode']) && $res['errcode'] != 45009) Common::res(['code' => 1, 'msg' => $res['errmsg']]);
            Common::res(['code' => 1, 'msg' => '上传图片失败，请联系客服']);
        }
    }

    public function log()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $filter = $this->req('filter');
        $logList = Rec::getList($this->uid, $page, $size, $filter);

        Common::res(['data' => $logList]);
    }

    /**补偿 */
    public function redress()
    {
        $this->getUser();

        $redressTime = UserExt::where('user_id', $this->uid)->value('redress_time');
        if ($redressTime) Common::res(['data' => ['status' => 1, 'msg' => '已领取补偿']]);

        $msg = '领取成功';
        // 领取24小时农场收益补偿和30钻石
        $update['coin'] = 24 * 3600 / 100 * UserSprite::where('user_id', $this->uid)->value('total_speed_coin');
        $msg .= '，金豆+' . $update['coin'];
        $update['stone'] = 30;
        $msg .= '，钻石+' . $update['stone'];

        (new User)->change($this->uid, $update, '农场补偿');
        UserExt::where('user_id', $this->uid)->update(['redress_time' => time()]);

        Common::res(['data' => ['status' => 0, 'msg' => $msg]]);
    }

    public function gzhPushSubscribe()
    {
        $this->getUser();
        $push_id = $this->req('push_id', 'require');
        $checked = $this->req('checked', 'require');

        GzhUserPush::setData($this->uid, $push_id, $checked);
        Common::res();
    }
}
