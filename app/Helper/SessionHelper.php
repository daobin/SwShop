<?php
/**
 * 会话管理助手
 * User: dao bin
 * Date: 2021/7/15
 * Time: 14:45
 */
declare(strict_types=1);

namespace App\Helper;

class SessionHelper
{
    const SS_NAME = 'SW_SHOP';
    private $request;
    private $response;
    private $redis;
    private $sid;
    private $expire;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->redis = RedisHelper::openRedis();
        $this->expire = (int)ConfigHelper::get('redis.expire', 1800);
    }

    public function start($domain)
    {
        $this->sid = $this->parseSessionId();
        if (empty($this->sid)) {
            $this->sid = $this->createSessionId();
        } else if (!($this->redis->exists(self::SS_NAME . '_' . $this->sid))) {
            $this->sid = $this->createSessionId();
        }

        $expire = time() + $this->expire;
        $this->response->cookie(self::SS_NAME, $this->sid, $expire, '/', $domain, false, true);
        $this->set(self::SS_NAME, $this->sid);
        $this->redis->expire(self::SS_NAME . '_' . $this->sid, $this->expire);
    }

    public function set($field, $value)
    {
        $key = self::SS_NAME . '_' . $this->sid;
        $this->redis->hSet($key, $field, $value);
    }

    public function get($field, $default = null)
    {
        $key = self::SS_NAME . '_' . $this->sid;
        $value = $this->redis->hGet($key, $field);
        return empty($value) ? $default : $value;
    }

    public function getAll()
    {
        $key = self::SS_NAME . '_' . $this->sid;
        return $this->redis->hGetAll($key);
    }

    public function remove($field)
    {
        $key = self::SS_NAME . '_' . $this->sid;
        $this->redis->hDel($key, $field);
    }

    public function clear()
    {
        $this->redis->del(self::SS_NAME . '_' . $this->sid);
    }

    private function parseSessionId(): ?string
    {
        if (isset($this->request->cookie[self::SS_NAME])) {
            return (string)$this->request->cookie[self::SS_NAME];
        }

        return null;
    }

    private function createSessionId(): string
    {
        $sid = random_bytes(8);
        $sid = bin2hex($sid) . time();
        return strtoupper($sid);
    }
}
