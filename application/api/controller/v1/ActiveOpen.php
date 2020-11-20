<?php


namespace app\api\controller\v1;

use app\api\model\Cfg;
use app\api\model\CfgActiveOpen;
use app\api\model\RecUserCourage;
use app\api\model\RecUserCourageBox;
use app\api\model\UserAnimal;
use app\api\model\StarRank;
use app\api\model\UserManor;
use app\api\model\UserStar;
use app\base\service\Common;
use app\base\controller\Base;

class ActiveOpen extends Base
{

    /**
     * 勇气排行
     */
    public function getRankList()
    {
        $this->getUser();
        $page = input('page', 1);
        $size = input('size', 10);
        $type = input('type', 'user');
        $star_id = input('star_id', '');
        $rankField = input('rankField', 'courage');

        if ($type == 'user') {
            if ($star_id) {
                $list = UserStar::with(['User', 'Star'])->where('star_id', $star_id)->where('courage', '>', 0)->field('id,user_id,star_id,courage,courage_last_time')->order($rankField . ' desc,courage_last_time asc')->page($page, $size)->select();
            } else {
                $list = UserStar::with(['User', 'Star'])->where('courage', '>', 0)->field('id,user_id,star_id,courage,courage_last_time')->order($rankField . ' desc,courage_last_time asc')->page($page, $size)->select();
            }
            $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);
            foreach ($list as &$value) {
                $value['animal_img'] = UserManor::getManorAnimal($value['user_id']);
                if (!$value['animal_img']) {
                    $value['animal_img'] = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HrdPxWibdec5B8xuaXDQD2XzHp4Doo1UtWJykW8zbWamkc2sRYJMKuBiaJzoMDyGibcF025KvDNlSOw/0';
                }
            }
        } elseif ($type == 'star') {
            if ($page > 10) {
                $list = [];
            } else {
                if($size<10) $page=1;
                $list = StarRank::with('star')->where('courage', '>', 0)->field('id,star_id,courage,courage_last_time')->order($rankField . ' desc,courage_last_time asc')->page($page, $size)->select();
                foreach ($list as &$v) {
                    $v['userStar'] = UserStar::with('User')->where('star_id', $v['star_id'])->where('courage', '>', 0)->field('id,user_id,star_id,courage,courage_last_time')->order($rankField . ' desc,courage_last_time asc')->limit(3)->select();
                }
            }
        }

        Common::res(['data' => $list]);
    }

    /**
     * 冒险信息
     */
    public function getAdventureInfo()
    {
        $this->getUser();
        $res['my_manor_output'] = UserManor::where('user_id', $this->uid)->value('output');
        $res['my_courage'] = UserStar::where('user_id', $this->uid)->value('courage');
        $pet_adventure = Cfg::getCfg('pet_adventure');
        $star_reward = $pet_adventure['reward'];
        $userAnimal = UserAnimal::with('Animal')->where('user_id', $this->uid)->field('id,animal_id,level')->select();
        $res['my_first_list'] = [];
        $res['my_second_list'] = [];
        $res['my_third_list'] = [];
        $res['my_first_courage'] = 0;
        $res['my_second_courage'] = 0;
        $res['my_third_courage'] = 0;
        foreach ($userAnimal as $value) {
            if (!$value['animal']) continue;
            if(!$value['animal']['adventure_type']) continue;
            $adventure_type = $value['animal']['adventure_type'];
            $addnum = $star_reward[$adventure_type][$value['level'] - 1];
            $value['courage_value'] = $addnum;
            if ($adventure_type == RecUserCourage::NORMAL) {
                $res['my_second_courage'] += $addnum;
                $res['my_second_list'][] = $value;
            } elseif ($adventure_type == RecUserCourage::SUPER_SECRET) {
                $res['my_third_courage'] += $addnum;
                $res['my_third_list'][] = $value;
            } else {
                $res['my_first_courage'] += $addnum;
                $res['my_first_list'][] = $value;
            }
        }
        $date_time = RecUserCourage::checkTime();
        $res['my_first_is_settle'] = RecUserCourage::where('user_id', $this->uid)->where('type', RecUserCourage::OTHER)->where('date_time', $date_time)->count();
        $res['my_second_is_settle'] = RecUserCourage::where('user_id', $this->uid)->where('type', RecUserCourage::NORMAL)->where('date_time', $date_time)->count();
        $res['my_third_is_settle'] = RecUserCourage::where('user_id', $this->uid)->where('type', RecUserCourage::SUPER_SECRET)->where('date_time', $date_time)->count();

        Common::res(['data' => $res]);
    }

    /**
     * 冒险
     */
    public function getAdventureReward()
    {
        $this->getUser();
        $type = $this->req('type', 'require');
        $status = Cfg::checkConfigTime('pet_adventure');
        if (empty($status)) {
            Common::res(['code' => 1, 'msg' => '活动已结束']);
        }
        if ($type != RecUserCourage::NORMAL && $type != RecUserCourage::SUPER_SECRET && $type != RecUserCourage::OTHER) Common::res(['code' => 1, 'msg' => '类型错误']);
        $res = RecUserCourage::getReward($this->uid, $type);
        Common::res(['data' => $res]);
    }

    /**
     * 冒险日志
     */
    public function courageLog()
    {
        $this->getUser();
        $page = $this->req('page', 'integer', 1);
        $size = $this->req('size', 'integer', 10);
        $logList = RecUserCourage::getList($this->uid, $page, $size);

        Common::res(['data' => $logList]);
    }

    /**
     * 开屏占领列表
     */
    public function getOpenList()
    {
        $this->getUser();
        $list = CfgActiveOpen::with(['User', 'Star'])->select();
        foreach ($list as &$value) {
            $value['date_text'] = date("m", strtotime($value['start'])) . '月' . date("d", strtotime($value['start'])) . '日';
            if (date('Y-m-d') > $value['start']) $value['timeout'] = true;
        }
        Common::res(['data' => $list]);
    }

    /**
     * 占领开屏
     */
    public function occupyOpen()
    {
        $this->getUser();
        $id = $this->req('id', 'require');
        $status = Cfg::checkConfigTime('pet_adventure');
        if (empty($status)) {
            Common::res(['code' => 1, 'msg' => '活动已结束']);
        }
        CfgActiveOpen::occupy($this->uid, $id);
        Common::res([]);
    }

    /**
     * 宝箱列表
     */
    public function getBoxList()
    {
        $this->getUser();
        $type = $this->req('type', 'require');
        $list = RecUserCourageBox::getList($this->uid, $type);

        Common::res(['data' => $list]);
    }

    /**
     * 宝箱奖励
     */
    public function getBoxReward()
    {
        $this->getUser();
        $type = $this->req('type', 'require');
        $index = $this->req('index', 'require');
        $status = Cfg::checkConfigTime('pet_adventure');
        if (empty($status)) {
            Common::res(['code' => 1, 'msg' => '活动已结束']);
        }
        if ($type != 'box' && $type != 'share_box' && $type != 'share_big_box' && $type != 'big_box') Common::res(['code' => 1, 'msg' => '类型错误']);
        $res = RecUserCourageBox::getReward($this->uid, $type, $index);
        Common::res(['data' => $res]);
    }


}