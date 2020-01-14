<?php

namespace app\api\model;

use app\base\model\Base;

/**新年拉人活动 */
class ActiveLaren extends Base
{
    public static function getList($uid)
    {
        $star_id = UserStar::getStarId($uid);

        $cateList = [
            // TODO
            1 => ['title' => '生日应援大屏', 'list' => []],
            2 => ['title' => '2020年小程序24小时开屏', 'list' => []],
            3 => ['title' => '爱心兑好礼（2月14日开启）', 'list' => []],
        ];

        foreach (self::all() as &$value) {
            // 我的爱豆
            $value['self'] = LarenStar::with(['star'])->where('active_id', $value['id'])->where('star_id', $star_id)->find();
            // 是否已解锁
            $value['achieve'] = $value['self']['is_achieve'];

            // 第一名
            $value['top'] = LarenStar::with(['star'])->where('active_id', $value['id'])->order('count desc')->find();
            if ($value['top']['star_id'] == $star_id) {
                // 第二名
                $value['self'] = LarenStar::with(['star'])->where('active_id', $value['id'])->where('count', '<', $value['top']['count'])->order('count desc')->find();
                if ($value['self']) $value['self']['rank'] = 2;
            } else {
                if ($value['self']) $value['self']['rank'] = LarenStar::where('count', '>', $value['self']['count'])->count() + 1;
            }


            $cateList[$value['cate_id']]['list'][] = $value;
        }

        return $cateList;
    }
}
