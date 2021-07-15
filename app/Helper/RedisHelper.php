<?php
/**
 * Redis 客户端助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 11:50
 */
declare(strict_types=1);

namespace App\Helper;

class RedisHelper
{
    /**
     * Redis 客户端
     */
    private static $redis;

    /**
     * Redis 服务器开启并检测
     * @return null|\Redis
     */
    public static function openRedis(): ?\Redis
    {
        if (self::$redis) {
            try{
                self::$redis->ping();
            }catch (\RedisException $e){
                self::$redis = null;
                self::openRedis();
            }
            return self::$redis;
        }

        try {
            $redisCfgs = ConfigHelper::get('database.redis');

            self::$redis = new \Redis();
            self::$redis->connect($redisCfgs['host'], $redisCfgs['port'], 0.2);
            self::$redis->auth($redisCfgs['auth']);
        } catch (\RedisException $e) {
            self::$redis = null;
        }

        return self::$redis;
    }
}
