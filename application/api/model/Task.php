<?php

namespace app\api\model;

use app\api\service\Task as TaskService;
use app\base\model\Base;

class Task extends Base
{
    //

    public function TaskType()
    {
        return $this->belongsTo('TaskType', 'type', 'id');
    }
}
