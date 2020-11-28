<?php


namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\RecUserHelpNum;
use app\api\model\RecUserHelpNumTask;
use app\api\model\StarRank;
use app\api\model\UserManor;
use app\api\model\UserProp;
use app\api\model\UserStar;
use app\base\service\Common;
use app\base\controller\Base;

class BirthdayOpen extends Base
{

    /**
     * 开屏任务信息
     */
    public function getTaskInfo()
    {
        $this->getUser();
        RecUserHelpNumTask::getOldReward($this->uid);
        $res = Cfg::getCfg('birthday_open');
        foreach ($res['help_task_list'] as $key=>&$value){
            $value['status'] = 0;
            $value['count'] = 0;

            if($value['type'] == 'coin_flower'){
                $value['count'] = UserStar::where('user_id',$this->uid)->value('total_count');
            }
            if($value['type'] == 'flower'){
                $value['count'] = UserStar::where('user_id',$this->uid)->value('total_flower');
            }
            if($value['type'] == 'lukey_prop'){
                $value['count'] = UserProp::where('user_id',$this->uid)->where('prop_id',$value['prop_id'])->count();
            }
            $task = RecUserHelpNumTask::where('user_id',$this->uid)->where('index',$key)->find();
            if($task){
                if($value['count']!=$task['done_times']){
                    $task['done_times'] = $value['count'];
                }
                $value['count'] = $task['done_times']-$task['is_settle_times'];
                $get_help_num = ($task['done_times']-$task['is_settle_times'])/$value['need_num'];
                if($get_help_num>=1){
                    $value['status'] = 1;
                    $value['get_help_num'] = floor($get_help_num) * $value['help_num'];
                }
            }else{
                $get_help_num = $value['count']/$value['need_num'];
                if($get_help_num>=1){
                    $value['status'] = 1;
                    $value['get_help_num'] = floor($get_help_num) * $value['help_num'];
                }
            }
        }

        Common::res(['data' => $res]);
    }

    public function settleTask()
    {
        $this->getUser();
        $index = $this->req('index', 'integer');
        $earn = RecUserHelpNumTask::getReward($this->uid, $index);

        Common::res(['data' => $earn]);
    }

    /**
     * 领取日志
     */
    public function helpLog()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 15);
        $logList = RecUserHelpNum::getList($this->uid, $page, $size);

        Common::res(['data' => $logList]);
    }

    /**
     * 助力值排行
     */
    public function getRankList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $type = input('type', 'user');
        $star_id = input('star_id', '');

        if ($type == 'user') {
            $where = $star_id ? ['star_id' => $star_id] : '1=1';
            $list = UserStar::with(['User', 'StarInfo'])->where($where)->where('help_num', '>', 0)->field('id,user_id,star_id,help_num,help_num_last_time')->order('help_num desc,help_num_last_time asc')->page($page, $size)->select();
            $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
            foreach ($list as $key => $value) {
                $list[$key]['animal_img'] = UserManor::getManorAnimal($value['user_id']);
                if (!$list[$key]['animal_img']) {
                    $list[$key]['animal_img'] = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HrdPxWibdec5B8xuaXDQD2XzHp4Doo1UtWJykW8zbWamkc2sRYJMKuBiaJzoMDyGibcF025KvDNlSOw/0';
                }
            }
            if ($page == 1 && !$star_id) {
                if(count($list)>1){
                    foreach ($list as $k => $v) {
                        if ($k == 0) {
                            $list[$k + 1] = $v;
                        }
                        if ($k == 1) {
                            $list[$k - 1] = $v;
                        }
                    }
                }else{
                    foreach ($list as $k => $v) {
                        if ($k == 0) {
                            $list[1] = $v;
                            $list[$k] = [];
                            $list[2] = [];
                        }
                    }
                }

            }

        } elseif ($type == 'star') {
            if ($page > 10) {
                $list = [];
            } else {
                if ($size < 10) $page = 1;
                $list = StarRank::with('star')->where('help_num', '>', 0)->field('id,star_id,help_num,help_num_last_time')->order('help_num desc,help_num_last_time asc')->page($page, $size)->select();
                foreach ($list as &$v) {
                    $v['userStar'] = UserStar::with('User')->where('star_id', $v['star_id'])->where('help_num', '>', 0)->field('id,user_id,star_id,help_num,help_num_last_time')->order('help_num desc,help_num_last_time asc')->limit(3)->select();
                    if(strlen($v['star']['birthday'])==3){
                        $v['star']['birthday_text'] = substr($v['star']['birthday'],0,1).'月'.substr($v['star']['birthday'],1,2).'日';
                    }
                    if(strlen($v['star']['birthday'])==4){
                        $v['star']['birthday_text'] = substr($v['star']['birthday'],0,2).'月'.substr($v['star']['birthday'],2,2).'日';
                    }
                }
            }
        }

        Common::res(['data' => $list]);
    }

}