<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Model;

class CfgTaskgift extends Base
{
    public static function listHandle($cid, $list, $uid)
    {
        switch ($cid) {
            case 1:
                // 登录礼包
                $signDays = NewboySignin::getProgress($uid);

                foreach ($list as &$value) {
                    $value['awards'] = json_decode($value['awards']);

                    if ($value['count'] <= $signDays) {
                        $value['over'] = true;
                    }
                }
                break;
            case 2:
                // 充值礼包TODO:
                // $signDays = NewboySignin::getProgress($uid);

                foreach ($list as &$value) {
                    $value['awards'] = json_decode($value['awards']);
                }
                break;

            default:
                # code...
                break;
        }

        return $list;
    }

    /**礼包领取 */
    public static function settleHandle($cid, $list, $uid)
    {
        switch ($cid) {
            case 1:
                // 登录礼包
                NewboySignin::getSettle($uid, $list);
                break;

            case 2:
                // Common::res(['c'])

                // TODO:
                break;

            default:
                # code...
                break;
        }
    }
}
