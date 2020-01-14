<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Article as ArticleModel;
use app\base\service\Common;
use app\api\model\ArticleExtra;
use app\api\model\Hotsearch;
use app\api\model\HotsearchFetch;
use app\api\model\Notice;
use app\api\model\UserStar;

class Article extends Base
{

    public function getArticle()
    {
        $id = $this->req('id', 'integer');

        $res = ArticleModel::with('user')->where('id', $id)->find();
        $res['imgs'] = json_decode($res['imgs']);

        Common::res(['data' => $res]);
    }

    public function getArticleExtra()
    {
        $id = $this->req('id', 'integer');
        $active = $this->req('active');
        if ($active == 'comment') {
            $type = 0;
        } else if ($active == 'like') {
            $type = 1;
        } else if ($active == 'share') {
            $type = 2;
        }

        $res = ArticleExtra::with('user')->where('article_id', $id)->where('type', $type)->select();
        Common::res(['data' => $res]);
    }

    public function getList()
    {
        $page = input('page', 1);
        $size = input('size', 10);

        $star_id = $this->req('star_id', 'require');
        $res = ArticleModel::getList($star_id, $page, $size);
        Common::res(['data' => $res]);
    }

    /** 删除文章 */
    public function delete()
    {
        $id = $this->req('id', 'integer');
        ArticleModel::destroy($id);
        Common::res();
    }

    public function subscribe()
    {
        $this->getUser();

        $curSubscribeStatus = UserStar::where('user_id', $this->uid)->value('article_subscribe');
        UserStar::where('user_id', $this->uid)->update([
            'article_subscribe' => !$curSubscribeStatus
        ]);

        Common::res(['data' => ['sub' => (int) !$curSubscribeStatus]]);
    }

    public function hotsearch()
    {
        // 微博热搜
        $fetchArr = json_decode(HotsearchFetch::where('1=1')->order('id desc')->value('content'), true);
        // 已订阅明星name关键字
        $matchArr = Db::query("SELECT s.id, s.name FROM `f_user_star` u join f_star s on s.id = u.star_id where article_subscribe = 1 group by u.star_id;");

        foreach ($fetchArr as $fetch) {
            foreach ($matchArr as $match) {
                if (strpos($fetch['content'], $match['name']) !== false) {
                    $fetch['star_id'] = $match['id'];
                    Hotsearch::saveAndPush($fetch);
                }
            }
        }
    }

    public function getVideoExpire()
    {
        $res = ArticleModel::where('video_expires', '<', time())->where('video', '<>', 0)->column('weibo_id');
        $html = '';
        foreach ($res as $value) {
            $html .= "<a href='https://m.weibo.cn/detail/{$value}'>{$value}</a></br>";
        }

        echo $html;
    }

    public function getNotice()
    {
        $id = $this->req('id', 'integer');

        $res = Notice::get($id);
        Common::res(['data' => $res]);
    }

    public function getNoticeList()
    {
        $res = Notice::where('1=1')->order('is_top desc,create_time desc')->select();
        Common::res(['data' => $res]);
    }

    public function refrashVideo()
    {
        $id = $this->req('id', 'integer');

        $weiboUrl = 'https://m.weibo.cn/detail/' . ArticleModel::where('id', $id)->value('weibo_id');

        $weiboContent = Common::request($weiboUrl);
        preg_match("/\"stream_url\": \"(.+?)\",/", $weiboContent, $match);

        // 新的视频地址
        $newSrc = '';
        if (isset($match[1])) {
            $newSrc = $match[1];
            ArticleModel::where('id', $id)->update(['video' => $newSrc]);
        }

        Common::res(['data' => $newSrc]);
    }

    public function formart()
    {
        $text = input('text');

        $result = [];
        $text = explode(';', $text);
        foreach ($text as $row) {
            if (strpos($row, '=') !== false) {
                $split = explode('=', $row);

                $left = trim($split[0]);
                $right = trim($split[1]);

                if ($left == '标题') $left = 'title';
                if ($left == '内容') $left = 'content';
                if ($left == '图片') $left = 'image';

                $result[] = [
                    'type' => $left,
                    'content' => $right,
                ];
            }
        }
        return view('formart', ['text' => json_encode($result, JSON_UNESCAPED_UNICODE)]);
    }
}
