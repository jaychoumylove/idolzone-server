<?php

namespace app\api\model;

use think\Model;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;
use app\api\service\User;

class UserProp extends Base
{
    public function Prop()
    {
        return $this->belongsTo('Prop', 'prop_id', 'id');
    }

    /**
     * 增加道具
     * @param int $prop_id 
     * @param int $num 增加数量 
     */
    public static function addProp($user_id, $prop_id, $num)
    {
        $insert = [];
        for ($i = 0; $i < $num; $i++) {
            $insert[] = [
                'user_id' => $user_id,
                'prop_id' => $prop_id,
            ];
        }

        self::insertAll($insert);
    }

    public static function getList($uid)
    {
        $list = self::with('Prop')->where('user_id', $uid)->order('id desc')->select();

        // 检查是否已过期
        foreach ($list as &$value) {
            if ($value['status'] == 0 && date('Ymd', strtotime($value['create_time'])) != date('Ymd')) {
                // 购买的道具仅限当天使用
                $value['status'] = 2;
                self::where('id', $value['id'])->update(['status' => 2]);
            }
        }

        return $list;
    }

    /**使用道具 */
    public static function use($id)
    {
        $prop = self::get($id);
        if (!$prop || $prop['status'] != 0) Common::res(['code' => 3, 'msg' => '道具无法使用']);

        $res = [];
        Db::startTrans();
        try {
            switch ($prop['prop_id']) {
                case 1:
                    // 可偷金豆增加卡

                    break;
                case 2:
                    // 精灵生产加速卡
                    // 是否有正在使用中的卡
                    $usingTime = 2 * 3600;

                    // 最近使用该类型道具时间
                    $least_use_time = self::where(['user_id' => $prop['user_id'], 'prop_id' => $prop['prop_id']])->max('use_time');
                    if ($least_use_time > time() - $usingTime) {
                        Common::res(['code' => 1, 'msg' => '抱歉，无法叠加']);
                    }

                    break;
                case 3:
                    // 送礼物获得额外金豆卡
                    break;
                case 4:
                    // 金豆福袋
                    $awards = [10000, 12000, 15000, 20000];
                    $rd = mt_rand(0, 3);
                    (new User())->change($prop['user_id'], [
                        'coin' => $awards[$rd]
                    ], ['type' => 26]);
                    $res['awards'] = $awards[$rd];
                default:
                    # code...
                    break;
            }

            self::where('id', $id)->update([
                'status' => 1,
                'use_time' => time()
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $res;
    }

}
