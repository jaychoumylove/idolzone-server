<?php
namespace app\push\service;

use GatewayWorker\Lib\Gateway;

class Bind
{

    public function __construct()
    {
        Gateway::$registerAddress = '172.19.102.5:10082';
    }

    public function Bind($client_id, $uid)
    {
        Gateway::bindUid($client_id, $uid);
    }

}
