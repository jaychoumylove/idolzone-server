<?php

namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\Article as ArticleModel;
use app\base\service\Common;
use app\api\model\ArticleExtra;
use app\api\model\Notice;
use app\api\model\UserStar;
use think\Db;

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
        $id = $this->req('id','integer');

        $res = Notice::get($id);
        Common::res(['data' => $res]);
    }

    public function getNoticeList()
    {
        $res = Notice::where('1=1')->order('is_top desc,create_time desc')->select();
        Common::res(['data' => $res]);
    }
}
