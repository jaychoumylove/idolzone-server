<?php

namespace app\api\controller;

use app\api\model\Article;
use think\Controller;
use app\base\service\Common;
use app\api\model\StarRank;
use app\api\model\Star;
use think\Db;
use think\Log;
use think\Cache;
use app\base\service\WxAPI;
use think\Exception;
use app\base\controller\Base;
use app\base\service\WxMsg;
use app\api\model\PayOrder;
use app\api\model\RecPayOrder;
use app\api\model\Cfg;
use app\api\model\CfgLottery;
use app\api\model\CfgUserLevel;
use app\api\model\Fanclub;
use app\api\model\Lock;
use app\api\model\PayGoods;
use app\api\model\RecCardHistory;
use app\api\model\RecStarChart;
use app\api\model\User;
use app\api\model\UserCurrency;
use app\api\model\UserFather;
use app\api\model\Wxgroup;
use app\api\service\Redis;
use ReflectionClass;
use ReflectionObject;

class Test extends Base
{

    public function getToken()
    {
        echo Common::setSession(input('uid') / 1234);
    }

    public function getUid()
    {
        echo Common::getSession(input('token'));
    }

    public function index()
    {
        echo 1;
        // echo strtotime;
        // Fanclub::where('1=1')->update(['wx' => 1]);
        // echo $_SERVER['SERVER_ADDR'];
        // $redis = new Redis;
        // echo $redis->get('a');

        // $list = StarRank::with(['star'])->order('week_hot desc')->field('id,star_id')->limit(100)->select();

        // foreach ($list as $value) {
        //     for ($i = 1; $i <= 1; $i++) {
        //         echo "<a href='https://m.weibo.cn/api/container/getIndex?containerid=100103"
        //             . urlencode('type=60&q=' . $value['star']['name'] . '&t=0') . "&page_type=searchall&page={$i}'>{$value['star']['name']}|{$value['star_id']}</a></br>";
        //     }
        // }
        // echo gettype(1);

        // PayGoods::where('remain', 'not null')->update(['remain' => 100]);

        // echo config('database.prefix');
        // echo UserCurrency::where('uid', 104068)->value('coin');
        // dump(CfgUserLevel::getLevel(104068));
        // return Common::request('https://s.weibo.com/top/summary?cate=realtimehot');

        // Article::where('id', 6)->update(['title' => '🍅']);
        // return view();
        // Wxgroup::dayInit();

        // die('Hello World!');

        // $this->getUser();
        // $appid = (new WxAPI())->appinfo['appid'];
        // $sessionKey = User::where(['id' => $this->uid])->value('session_key');

        // $encryptedData = input('encryptedData');
        // $iv = input('iv');

        // $res = Common::wxDecrypt($appid, $sessionKey, $encryptedData, $iv);

        // Common::res(['data' => $res]);

        // echo strtotime(date('Y-m-01') . ' +1 month');
        // Db::startTrans();
        // Lock::where('1=1')->update([
        //     'value' => 0
        // ]);




        // RecCardHistory::settle();
        // $res = Db::query("SELECT s.name,count(u.id) as count FROM `f_user_star` u join
        // f_star s on s.id = u.star_id where u.active_card_days >= 7 and u.delete_time is null GROUP BY u.star_id ORDER BY count desc LIMIT 10;");


        // Common::res(['data' => $res]);

        // // $arr = [
        // //     'fee' => '100'
        // // ];
        // $a = --self::$fee;
        // echo $a;

        // echo strtotime('-10 day');
        // echo $this->req('a','integer',1);


        // echo (int) 'eeeeeeee';
        // echo strtotime(date('Y-m-d', strtotime('this week') + 7 * 3600 * 24));


        // Db::execute("UPDATE `f_cfg` SET `value`='' WHERE (`key`='recharge_title');");

        // Db::execute("UPDATE `f_pay_goods` SET `fee`='2', `off_fee`='0' WHERE (`id`='1')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='5', `off_fee`='0' WHERE (`id`='2')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='10', `off_fee`='0' WHERE (`id`='3')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='30', `off_fee`='0' WHERE (`id`='4')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='60', `off_fee`='0' WHERE (`id`='5')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='100', `off_fee`='0' WHERE (`id`='6')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='200', `off_fee`='0' WHERE (`id`='7')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='500', `off_fee`='0' WHERE (`id`='8')");
        // Db::execute("UPDATE `f_pay_goods` SET `fee`='880', `off_fee`='0' WHERE (`id`='9')");


        // dump( $_GET);
        // echo gettype([1,2]); // integer string boolean double array
        // $class = new \ReflectionClass('app\api\model\PayOrder');
        // dump($class->getMethods());

        // $res = UserFather::with('f')->field('father,sum(cur_contribute) as sum')
        //     ->group('father')->having('sum <> 0')->order('sum desc')->limit(20)->select();

        // Common::res(['data' => $res]);
        // echo $_SERVER['HTTP_HOST'];
        // echo $_SERVER['HTTP_HOST'];
        // Log::record(11);
        // dump((new WxAPI())->msgCheck('中共的爆政'));
        // $res = RecStarChart::verifyWord('1231313', true);

        // dump($res);
        // $lotteryList = CfgLottery::where('1=1')->order('chance asc')->select();
        // $totalPt = CfgLottery::where('1=1')->sum('chance');
        // $randPt = mt_rand(0, $totalPt);
        // $base = 0;
        // foreach ($lotteryList as $value) {
        //     if ($randPt < $value['chance'] + $base) {
        //         $lottery = $value;
        //         break;
        //     } else {
        //         $base += $value['chance'];
        //     }
        // }

        // Common::res(['data' => $lottery]);
        // echo strtotime('00:00:00');
        // Log::record(111);
        // Common::requestAsync('https://' . $_SERVER['HTTP_HOST'] . '/api/v1/auto/sendTmp',[
        //     'starid' => 35,
        //     'fee' => 100
        // ]);
        // dump(fsockopen("www.baidu.com", 80, $errno, $errstr, 30));
        // $wxApi = new WxAPI();
        // dump($wxApi->sendTemplateSync());
        // $start = time();
        // for ($i = 0; $i < 1000000000; $i++) { }
        // echo time() - $start;
        // echo date('Y-m-d H:i:s', Cfg::getCfg('active_date')[0]);
        // echo (int) !0;

        // echo json_encode(
        //     [
        //         [
        //             'fee' => 100,
        //             'count' => 3000
        //         ],
        //         [
        //             'fee' => 200,
        //             'count' => 6000
        //         ],
        //         [
        //             'fee' => 500,
        //             'count' => 15000
        //         ],
        //         [
        //             'fee' => 1000,
        //             'count' => 30000
        //         ]
        //     ],
        //     JSON_UNESCAPED_UNICODE
        // );

        // Cache::set('monthOptime', 0);
        // echo date('Ym');
        // echo ceil(-1);
        // dump(json_decode('{"a":1}', true));

        // test('hello');
        // $wxApi = new WxAPI();
        // $data = $wxApi->getwxacode('/pages/index/index?starid=' . 1);

        // file_put_contents(ROOT_PATH . 'public/uploads/1.jpg', $data);

        // echo date('Ym', time() - 3600);

        // dump(file_get_contents('https://ww2.sinaimg.cn/large/005BYqpggy1g2u2xti6raj305k05ka9v.jpg'));
        // dump((new WxMsg())->getMediaId(ROOT_PATH . 'public/uploads/cust-0.jpg'));
        // echo strtotime('06:00:00');
        // dump((new WxAPI('gzh'))->addMaterial(ROOT_PATH . 'public/uploads/test/4.png'));
        // echo urlencode('https://rank.xiaolishu.com/#/pages/signin/signin');

        // $wxApi = new WxAPI('wx7dc912994c80d9ac');
        // dump($wxApi->createMenu());

        // echo strtotime('-1 day');

        // echo (int)null;

        // try {
        //     //code...

        //    throw new Exception('cuowu');

        // } catch (\Throwable $th) {
        //     //throw $th;
        //     echo $th->getMessage();
        // }
        // $res = $this->test();

        // die((string)213213);
        // echo date('y-m-d');
        // $res = (new WxAPI('wx7dc912994c80d9ac'))->uploadMedia(ROOT_PATH . 'public' . DS . 'test.png');
        // dump($res);

        //    cache(' test ',1);
        //    cache(' test ',2);

        //    dump(cache(' test '));

        // Cache::set(' lockSend ', [
        //     ' isLock ' => 1,
        //     ' time ' => time()
        // ]);
        // die(Log::record(' hello ', ' error '));
        // echo json_encode([
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        //     [
        //         ' title ' => ' ',
        //         "imageUrl"  => ' ',
        //     ],
        // ], true);
        // phpinfo();

        // ini_set(' memory_limit ', ' 128 M ');
        // $string = str_pad(' 1 ', 128 * 1024 * 1024);


        // include_once APP_PATH . ' wx / crypto / wxBizMsgCrypt . php ';
        // require_once APP_PATH . ' wx / aes / wxBizDataCrypt . php ';

        // dump(memory_get_usage());
        // Common::res([' data'=>1]);
    }
}
