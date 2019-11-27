<?php

namespace app\api\controller\v1;

use app\api\model\NewboySignin;
use app\base\controller\Base;
use app\base\service\Common;

class Newboy extends Base
{
    /**新人限时礼包 */
    public function gift()
    {
        $this->getUser();

        // $endDate = '2019-11-30 00:00:00';
        // $res['remainTime'] = strtotime($endDate) - time();
        // 登录礼包
        $res['bg'] = 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDNy4g4CU7wJTicw6PiaPhZmIsxMhu2kkD5iaDgmdFpDyg5yZYwJwibYXAUw/0';

        $img_list = [
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDXCibWQKfvh6CBRibdcrWhic8om1b5XcNqVBQIXmDjBGmWr51DupIlJdibQ/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDKy7pjpwCFT5grJtn69NHCgw20icbQ7rZHonicFvcrxC0D4iaJoyrtPlhw/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDE6WNB4UvxQb5DcZMzgzJXeaRoiaBibeUquZ8rrCSM5xGvRse2kXibTVGg/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDQxUb6icb7NH5MibZszfiaD25HsSqMNicrFX7YJQSCN33lXJsXYWBByucIg/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDXe0oKTFiayiaFsyyeicDianj151nSkrNBkT7B8ib0F79vl1dL3vqSxGMlLg/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDtk3rnRSQEKlI4ZsKD9H0SHlXOtd5JicZ4szT0libJbbmF98cO3GEJGyQ/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDz1SXApYZfGicvaactFetBeAhYmDUIYbsSE4MXb9v9bgiamico08PBavWA/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibID5HECksSIiaujicB8ZHdX6LG15ib3iaibyMy5O7qZLXpGPreesHZqXoda46g/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDb7B5nDqh66Mbs1RldSYrGh8XWyuN6hL9GswdC6oEa8jgKpic19POlaA/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDkNRTxibGIa6icqUJVVNicqoBMYTsfuugTrhcx7rmVNy3IL6PweZUBUuAw/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibID5E4vPjOHEDQ7l7E2yGVbK0hI87f1cfpceGXwuvia3pEfWkL3G80L6Qg/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDb7jtfU1Ps7zdcdwzch0UyoKzhaE25j4zX4x8bC8XHpvnD68aiaBWTqQ/0',
            ],
            [
                'to' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDvDvJgIPRZsrvicKk7MAibjP4esxh5N3Aj3SQkxMfGOuj7wUpchFZOxuA/0',
                'done' => 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ep3RhxrWX9ibdRVKkjMQibIDaU4JZaCmLvCdJLxedmWh0acMR6klgdeZ6gbjHUo0D6Mb6XfK3XsIibg/0',
            ],

        ];


        $days = NewboySignin::getProgress($this->uid);
        $res['list'] = [];
        foreach ($img_list as $key => $value) {
            if ($days >= $key + 1) {
                $res['list'][] = $value['done'];
            } else {
                $res['list'][] = $value['to'];
            }
        }

        Common::res(['data' => $res]);
    }

    /**领奖励 */
    public function settle()
    {
        $this->getUser();

        NewboySignin::getSettle($this->uid);
        Common::res();
    }
}
