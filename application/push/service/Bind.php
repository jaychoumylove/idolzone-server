<?php
namespace app\push\service;

use GatewayWorker\Lib\Gateway;

class Bind
{

    public function __construct()
    {
        Gateway::$registerAddress = '127.0.0.1:1238';
    }

    public function Bind($client_id, $uid)
    {
        Gateway::bindUid($client_id, $uid);
    }

}
