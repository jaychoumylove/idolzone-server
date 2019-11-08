<?php

namespace app\api\model;

use app\base\model\Base;
use app\base\service\Common;
use think\Db;

class User extends Base
{
    public function UserStar()
    {
        return $this->hasOne('UserStar', 'user_id', 'id', [], 'LEFT');
    }
    public function UserExt()
    {
        return $this->hasOne('UserExt', 'user_id', 'id', [], 'LEFT');
    }

    public function Sprite()
    {
        return $this->hasOne('UserSprite', 'user_id', 'id', [], 'LEFT');
    }

    /**
     * 检索用户
     * @return integer uid 用户id
     */
    public static function searchUser($data)
    {
        Db::startTrans();
        try {
            $user = self::get(['openid' => $data['openid']]);
            if (!$user) {
                // 创建新用户
                // User
                $insert = [
                    'openid' => $data['openid'],
                    'unionid' => isset($data['unionid']) ? $data['unionid'] : null,
                    'session_key' => isset($data['session_key']) ? $data['session_key'] : null,
                    'ident_code' => strtoupper(substr(md5(md5($data['openid'])), 0, 6)),
                    'platform' => isset($data['platform']) ? $data['platform'] : null,
                    'model' => isset($data['model']) ? $data['model'] : null,
                    'type' => isset($data['type']) ? $data['type'] : 0,
                    'nickname' => isset($data['nickname']) ? $data['nickname'] : null,
                    'avatarurl' => isset($data['avatarurl']) ? $data['avatarurl'] : null,
                ];

                $user = self::create($insert);
                // UserCurrency
                $currency = [
                    'uid' => $user['id'],
                ];
                if (isset($data['type']) && $data['type'] != 0) {
                    // 0 普通用户
                    $currency['coin'] =  100000;
                    $currency['flower'] =  10000;
                    $currency['stone'] =  300;
                    $currency['trumpet'] = 100;
                } else {
                    $currency['coin'] =  100;
                    $currency['flower'] =  100;
                    $currency['stone'] =  3;
                    $currency['trumpet'] = 1;
                }
                UserCurrency::create($currency);

                // UserExt
                UserExt::create([
                    'user_id' => $user['id'],
                ]);

                // UserSprite
                UserSprite::create([
                    'user_id' => $user['id'],
                    'settle_time' => time(), 
                ]);
            } else {
                if (isset($data['session_key'])) {
                    self::where(['openid' => $data['openid']])->update(['session_key' => $data['session_key']]);
                }
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Common::res(['code' => 400, 'msg' => $e->getMessage()]);
        }

        return $user['id'];
    }

    /**创建虚拟用户 */
    public static function createVirtualUser($data)
    {
        $vrNickname = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('nickname');
        $vrAvatar = OtherFakeUser::where('1=1')->orderRaw('rand()')->value('avatar');

        return self::searchUser([
            'openid' => $data['openid'],
            'unionid' => $data['unionid'],
            'nickname' => $vrNickname,
            'avatarurl' => $vrAvatar,
            'type' => 1 // 虚拟用户type
        ]);
    }

    /**创建机器人用户 */
    public static function createAndroid($vrNickname, $vrAvatar)
    {
        $rdCode = Common::getRandCode(24);

        return self::searchUser([
            'openid' => $rdCode,
            'unionid' => $rdCode,
            'nickname' => $vrNickname,
            'avatarurl' => $vrAvatar,
            'type' => 5 // 机器人用户type
        ]);
    }

    /**随机获取一个圈子内的机器人 */
    public static function getOneAndroid($starid, $limit_hot)
    {
        $uid = Db::name('user_star')->alias('s')->join('user u', 'u.id = s.user_id')
            ->where('u.type', 5)->where('s.star_id', $starid)->where('s.thisweek_count', '<', $limit_hot)
            ->orderRaw('rand()')->value('u.id');

        if (!$uid) Common::res(['code' => 1, 'msg' => '该圈子未找到Android']);

        return $uid;
    }
}
