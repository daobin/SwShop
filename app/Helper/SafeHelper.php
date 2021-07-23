<?php
/**
 * 安全助手
 * User: dao bin
 * Date: 2021/7/16
 * Time: 17:30
 */
declare(strict_types=1);

namespace App\Helper;

class SafeHelper
{
    const KEY = 'Ak@i+s526%!--like8=$';

    private $request;
    private $response;
    private $session;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->session = new SessionHelper($request, $response);
    }

    /**
     * 生成 CSRF 口令
     */
    public function buildCsrfToken(string $deviceFrom, string $operation): string
    {
        $field = $deviceFrom . $operation;
        $token = $this->session->get($field, '');
        if (!empty($token)) {
            return $token;
        }

        $token = $field . '$' . password_hash(time() . $this->request->server['request_uri'], PASSWORD_DEFAULT);
        $this->session->set($field, $token);
        return $token;
    }

    /**
     * 验证 CSRF 口令
     */
    public function chkCsrfToken(string $token): bool
    {
        $token = trim((string)$token);
        $field = empty($token) ? [] : explode('$', $token);
        if (empty($field)) {
            return false;
        }

        return $this->session->get(trim((string)reset($field)), '') == $token;
    }

    /**
     * 字符串加密
     */
    public static function encodeString(string $str): string
    {
        $str = trim($str);
        if ($str == '') {
            return $str;
        }

        $str = base64_encode(self::KEY . $str . self::KEY);
        $charArr = str_split($str);

        $str = [];
        foreach ($charArr as $idx => $char) {
            $str[$idx % 2][] = $char;
        }
        $str = implode('.e.', $str[0]) . '-..-' . implode('.o.', $str[1]);

        return base64_encode($str);
    }

    /**
     * 字符串解密
     */
    public static function decodeString(string $str): string
    {
        $str = trim($str);
        if ($str == '') {
            return $str;
        }

        $str = explode('-..-', base64_decode($str));
        if (count($str) != 2) {
            return '';
        }

        $charArr = [];
        $str[0] = explode('.e.', $str[0]);
        $str[1] = explode('.o.', $str[1]);
        foreach ($str[0] as $idx => $char) {
            $charArr[] = $char;
            if (!isset($str[1][$idx])) {
                break;
            }

            $charArr[] = $str[1][$idx];
        }

        $str = implode('', $charArr);
        $str = base64_decode($str);

        return str_replace(self::KEY, '', $str);
    }

}
