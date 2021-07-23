<?php
/**
 * Redis 客户端助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 11:50
 */
declare(strict_types=1);

namespace App\Helper;

use App\Biz\ConfigBiz;

class RedisHelper
{
    /**
     * Redis 服务器开启并检测
     */
    public function openRedis($redisHost, $redisPort, $redisAuth, $index)
    {
        $redis = new \Redis();
        $redis->connect($redisHost, $redisPort, 0.2);
        $redis->auth($redisAuth);
        $redis->select($index);

        return $redis;
    }
}
