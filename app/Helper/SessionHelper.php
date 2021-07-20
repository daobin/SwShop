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
        $this->redis = RedisHelper::openRedis($request->shop_id);
        $this->expire = (int)ConfigHelper::get('redis.expire', 1800);

        $this->start();
    }

    private function start()
    {
        $this->sid = '';
        if (isset($this->request->cookie[self::SS_NAME])) {
            $this->sid = (string)$this->request->cookie[self::SS_NAME];
        }
        if (empty($this->sid)) {
            $this->sid = $this->createSessionId();
        } else if (!($this->redis->exists(self::SS_NAME . '_' . $this->sid))) {
            $this->sid = $this->createSessionId();
        }

        // 防止第三方恶意获取客户会话ID
        $clientIp = $this->request->server['remote_addr'] ?? '';
        $chkSidArr = explode('_', $this->sid);
        if (count($chkSidArr) != 2 || (int)end($chkSidArr) != ip2long($clientIp)) {
            $this->sid = $this->createSessionId();
        }

        // 避免重复获取同一请求会话前后不一致的问题
        $this->request->cookie[self::SS_NAME] = $this->sid;

        // 更新响应会话
        $this->response->cookie(self::SS_NAME, $this->sid, time() + $this->expire, '/', $this->request->domain, false, true);
        $this->set(self::SS_NAME, $this->sid);
        $this->redis->expire(self::SS_NAME . '_' . $this->sid, $this->expire);
    }

    public function renameKey($domain)
    {
        $oldKey = self::SS_NAME . '_' . $this->sid;

        // 避免大量无用会话数据占用内存，直接将旧会话变更为新会话
        $this->sid = $this->createSessionId();
        $this->redis->rename($oldKey, self::SS_NAME . '_' . $this->sid);

        // 避免重复获取同一请求会话前后不一致的问题
        $this->request->cookie[self::SS_NAME] = $this->sid;

        // 更新响应会话
        $this->response->cookie(self::SS_NAME, $this->sid, time() + $this->expire, '/', $domain, false, true);
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

    private function createSessionId(): string
    {
        $clientIp = $this->request->server['remote_addr'] ?? '';

        $sid = random_bytes(8);
        $sid = bin2hex($sid) . time();
        $sid .= $clientIp ? '_' . ip2long($clientIp) : '';
        return strtoupper($sid);
    }
}
