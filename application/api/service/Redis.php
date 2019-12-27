<?php

namespace app\api\service;

use think\cache\driver\Redis as ThinkRedis;

class Redis extends ThinkRedis
{
    public function __construct()
    {
        try {
            parent::__construct([
                'host' => 'localhost'
            ]);
        } catch (\Throwable $e) {
            $this->connect = false;
        }
    }

    public function get($name, $default = false)
    {
        try {
            return parent::get($name, $default = false);
        } catch (\Throwable $th) {
            //throw $th;

            echo 'get失败';
        }
    }

    public function set($name, $value, $expire = null)
    {
        try {
            return parent::get($name, $value, $expire = null);
        } catch (\Throwable $th) {
            //throw $th;

            echo 'set失败';
        }
    }
}
