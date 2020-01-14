<?php

namespace app\api\model;

use app\api\service\User;
use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class LarenUser extends Base
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatarurl');
    }

    public function star()
    {
        return $this->belongsTo('Star', 'star_id', 'id')->field('id,name,head_img_s');
    }

    /**
     * 送爱心解锁
     * @param $uid
     * @param $aid 活动id
     * @param $count
     */
    public static function send($uid, $aid, $count)
    {
        // 活动信息
        $active = ActiveLaren::where('id', $aid)->find();
        // 我的爱心
        $myAixin = UserExt::where('user_id', $uid)->value('aixin');
        if ($myAixin < $count) Common::res(['code' => 1, 'msg' => '爱心不足']);

        Db::startTrans();
        try {
            UserExt::where('user_id', $uid)->update(['aixin' => Db::raw('aixin-' . $count)]);

            if ($active['cate_id'] == 3) {
                // 兑换虚拟货币
                if (date('md') < '0214') Common::res(['code' => 1, 'msg' => '02月14日开放']);
                if ($active['title'] == '金豆') $field = 'coin';
                if ($active['title'] == '鲜花') $field = 'flower';
                if ($active['title'] == '钻石') $field = 'stone';
                (new User)->change($uid, [
                    $field => $active['extra'] * $count
                ], '爱心兑换');
            } else {
                // 解锁大屏小屏
                $star_id = UserStar::getStarId($uid);
                // user
                $isDoneUser =  self::where('user_id', $uid)->where('star_id', $star_id)->where('active_id', $aid)->update([
                    'count' => Db::raw('count+' . $count)
                ]);
                if (!$isDoneUser) {
                    self::create([
                        'user_id' => $uid,
                        'star_id' => $star_id,
                        'active_id' => $aid,
                        'count' => $count
                    ]);
                }

                // star
                $isDoneStar = LarenStar::where('star_id', $star_id)->where('active_id', $aid)->update([
                    'count' => Db::raw('count+' . $count)
                ]);
                if (!$isDoneStar) {
                    LarenStar::create([
                        'star_id' => $star_id,
                        'active_id' => $aid,
                        'count' => $count
                    ]);
                }

                // 是否已解锁
                $curCount = LarenStar::where('star_id', $star_id)->where('active_id', $aid)->value('count');
                if ($curCount >= $active['achieve_count'] && $active['remain_count'] > 0) {
                    LarenStar::where('star_id', $star_id)->where('active_id', $aid)->update([
                        'is_achieve' => 1
                    ]);
                    ActiveLaren::where('id', $active['id'])->update(['remain_count' => Db::raw('remain_count-1')]);
                }
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }
    }

    public static function rank()
    {
        $list = self::with(['user', 'star'])->field('sum(count) as total, user_id, star_id')->group('user_id')->order('total desc')->limit(10)->select();
        return $list;
    }
}
