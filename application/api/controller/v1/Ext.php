<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\RecUserFormid;
use app\base\service\Common;
use app\api\model\CfgShareTitle;
use app\api\model\Cfg;
use app\api\model\CfgActive;
use app\api\model\UserStar;
use think\Db;
use app\base\service\WxAPI;
use app\api\model\Fanclub;
use app\api\model\Rec;
use app\api\model\UserSprite;
use app\api\model\RecActive;
use app\api\model\GuideCron;
use app\api\service\User;

class Ext extends Base
{

    public function saveFormId()
    {
        $formId = input('formId');
        if (!$formId) Common::res(['code' => 100]);
        $this->getUser();

        RecUserFormid::create([
            'user_id' => $this->uid,
            'form_id' => $formId,
        ]);
        Common::res([]);
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
        $starid = input('starid');
        $active_id = $this->req('id', 'integer');
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

        UserStar::setCard($this->uid, $starid, $active_id);
        Common::res();
    }

    public function userRank()
    {
        $starid =  input('starid');
        $page =  input('page', 1);
        $size =  input('size', 10);
        $active_id = $this->req('active_id', 'integer');

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
            // 上传到微信
            $res = (new WxAPI('wx00cf0e6d01bb8b01'))->uploadimg($realPath);
            $res['https_url'] = str_replace('http', 'https', $res['url']);
            unlink($realPath);
            Common::res(['data' => $res]);
        }
    }

    /**
     * 后援会入住
     */
    public function FanclubJoin()
    {
        $this->getUser();

        $find = Fanclub::where(['user_id' => $this->uid])->find();
        if ($find) Common::res(['code' => 1, 'msg' => '请勿重复提交']);

        $res['user_id'] = $this->uid;
        $res['avatar'] = input('avatar');
        $res['clubname'] = input('clubname');
        $res['name'] = input('name');
        $res['post'] = input('post');
        $res['wx'] = input('wx');
        $res['qualify'] = input('qualify');

        $res['star_id'] = UserStar::where('user_id', $this->uid)->value('star_id');

        Fanclub::create($res);
        Common::res([]);
    }

    public function fanclubList()
    {
        $star_id = input('star_id');
        $status = input('status', 2);
        $this->getUser();

        $list = Fanclub::getList($this->uid, $star_id, $status);
        Common::res(['data' => $list]);
    }

    public function joinFanclub()
    {
        $f_id = input('fanclub_id');
        $this->getUser();

        Fanclub::joinFanclub($this->uid, $f_id);
        Common::res();
    }

    public function exitFanclub()
    {
        $this->getUser();

        Fanclub::exitFanclub($this->uid);
        Common::res();
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

        // TODO:
        $endTime = '2019-11-11';
        if (time() > strtotime($endTime)) Common::res(['data' => ['status' => 1, 'msg' => '补偿已过期']]);

        $msg = '领取成功';
        // 领取24小时农场收益补偿和30钻石
        $update['coin'] = 24 * 3600 / 100 * UserSprite::where('user_id', $this->uid)->value('total_speed_coin');
        $msg .= '，金豆+' . $update['coin'];
        $update['stone'] = 30;
        $msg .= '，钻石+' . $update['stone'];

        (new User)->change($this->uid, $update, '农场补偿');

        Common::res(['data' => ['status' => 0, 'msg' => $msg]]);
    }

    public function score()
    {
        $this->getUser();

        // 我的积分
        $data['myScore'] = ScoreUser::myScore($this->uid);
        $data['task'] = Db::name('score_task')->order('sort asc')->select();

        // 任务
        $weiboPost = Db::name('task_weibo_log')->where('uid', $this->uid)->where('type', 0)
            ->whereTime('create_time', 'today')
            ->count('id');

        $weiboTrans = Db::name('task_weibo_log')->where('uid', $this->uid)->where('type', 1)
            ->whereTime('create_time', 'today')
            ->count('id');

        // 农场加速
        $speedEnd = Db::name('user_pet')->where('uid', $this->uid)->value('speed_end');

        // 每日首充
        $isFee = Db::name('order')->where('uid', $this->uid)->where('status', 1)->whereTime('create_time', 'today')->find();

        // 今日看视频次数
        $playVideoTimes = Db::name('score_log')->where('uid', $this->uid)->where('task_id', 3)->whereTime('create_time', 'today')->count('id');

        // 今日参加团战次数
        $pkJoinTimes = Db::name('score_log')->where('uid', $this->uid)->where('task_id', 4)->whereTime('create_time', 'today')->count('id');
        foreach ($data['task'] as &$task) {
            $task['status'] = 0;
            if ($task['id'] == 1) {
                // 微博转发
                if ($weiboTrans) {
                    $task['status'] = 2;
                }
            } else if ($task['id'] == 2) {
                // 微博发帖
                if ($weiboPost) {
                    $task['status'] = 2;
                }
            } else if ($task['id'] == 3) {
                // 看视频
                // 每天最多看20 次
                if ($playVideoTimes >= 20) {
                    $task['status'] = 2;
                }
            } else if ($task['id'] == 4) {
                // 团战
                if (!$pkJoinTimes) {
                    $pkJoinTimes = 0;
                }
                $task['desc'] .= '(' . $pkJoinTimes . '/4)';
            } else if ($task['id'] == 5) {
                // 农场加速
                if (date('Ymd') == date('Ymd',  $speedEnd)) {
                    $task['status'] = 2;
                }
            } else if ($task['id'] == 8) {
                // 贡献100万人气
                $scoreCount = ScoreTotal::where('uid', $this->uid)->value('count');
                if (!$scoreCount) {
                    $scoreCount = 0;
                }
                $task['desc'] .= '(' . $scoreCount . '/1000000)';
            } else if ($task['id'] == 9) {
                // 每日首充
                if ($isFee) {
                    $task['status'] = 2;
                }
            }
        }

        // 兑换
        $data['excharge'] = ScoreExchargeLog::myExcharge($this->uid);


        $data['cfg']['weibo_zhuanfa'] = json_decode($this->cfg['weibo_zhuanfa'], true);
        $data['cfg']['operate_cooling_time'] = $this->cfg['operate_cooling_time'];
        $data['cfg']['share_off'] = $this->cfg['share_off'];
        $data['cfg']['share_title_index'] = $this->cfg['share_title_index'];
        $data['cfg']['share_img_index'] = $this->cfg['share_img_index'];
        $share_img_index_arr = json_decode($this->cfg['share_img_index'], true);
        $data['cfg']['share_img_index'] = $share_img_index_arr[rand(0, count($share_img_index_arr) - 1)]['img'];


        return ['status' => 0, 'data' => $data];
    }
}
