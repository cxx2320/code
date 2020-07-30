<?php

class RedisClient {
    //创建静态私有的变量保存该类对象
    static private $instance;
    static private $redis;

     //防止直接创建对象
    private function __construct()
    {
        $redis = new \Redis();
        $redis->connect(config('redis.host'), config('redis.prot'));
        $redis->auth(config('redis.pwd'));
        self::$redis = $redis;
    }

    /**
     * 获取redis单例
     */
    static public function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    
    public function __call($name, $params)
    {
        if(empty($name)){
            return false;
        }
        return self::$redis->$name(...$params);
    }
    //防止克隆对象
    private function __clone(){}
}