<?php
namespace app\api\service;

use think\cache\driver\Redis as ThinkRedis;

class Redis extends ThinkRedis {

    public function __construct()
    {
        try {
            parent::__construct([
                'host' => 'localhost'
            ]);
        } catch (\Throwable $th) {
            
        }

        
        
    }
}