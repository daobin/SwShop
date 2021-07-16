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
    /**
     * 密钥 Key
     */
    const KEY = 'Ak@i+s526%!--like8=$';

    private $request;
    private $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 生成 CSRF 口令
     */
    public function buildCsrfToken($deviceFrom, $operation)
    {
        $session = new SessionHelper($this->request, $this->response);
        $field = $deviceFrom . $operation;
        $token = $session->get($field, '');
        if (!empty($token)) {
            return $token;
        }

        $token = $field . '$' . password_hash(time() . $request->server['request_uri'], PASSWORD_DEFAULT);
        $session->set($field, $token);
        return $token;
    }

    /**
     * 验证 CSRF 口令
     */
    public function chkCsrfToken($token)
    {
        $field = explode('$', (string)$token);
        if (empty($field)) {
            return false;
        }

        $session = new SessionHelper($this->request, $this->response);
        return $session->get(trim((string)reset($field)), '') == $token;
    }

    /**
     * 字符串加密
     */
    public static function encodeString(string $string): string
    {
        $string = trim($string);
    }

    /**
     * 字符串解密
     */
    public static function decodeString(string $string): string
    {

    }

    /**
     * 验证文件内容是否有安全风险
     */
    public static function chkFileSecurityIsRisk(string $filename): bool
    {
        // 需要检测的文件不存在视为有风险
        if (!file_exists($filename)) {
            return true;
        }

        // 读取长度前 1000 的内容
        $fd = @fopen($filename, 'rb');
        if (!$fd) {
            return true;
        }
        $content = fread($fd, 1000);
        $content = preg_replace('/[\s]+/', '', $content);
        $content = str_replace(['"', '\'', '.'], '', $content);
        fclose($fd);
        if (
            stripos($content, '<?php') !== false
            || stripos($content, 'create_function') !== false
            || stripos($content, 'eval') !== false
            || stripos($content, 'system') !== false
            || stripos($content, 'assert') !== false
            || stripos($content, 'base64_decode') !== false
        ) {
            return true;
        }

        return false;
    }
}
