<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\History;
use app\base\service\Common;

class Cleaner extends Base
{

    /**转存记录表数据 */
    public function index()
    {
        $clearTime = date('Y-m-d H:i:s', time() - 3600 * 24 * input('day', 30));
        $tableNameList = ['f_rec', 'f_rec_mass', 'f_rec_star_chart', 'f_rec_task'];

        foreach ($tableNameList as $tableName) {
            $list = Db::table($tableName)->whereTime('create_time', '<', $clearTime)->select();
            $jsonData = json_encode($list, JSON_UNESCAPED_UNICODE);

            History::create([
                'table_name' => $tableName,
                'data' => $jsonData,
                'clear_time' => $clearTime,
            ]);
        }

        Common::res(['msg' => 'success']);
    }
}
