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

    private $shopId;
    private $request;
    private $response;
    private $session;

    public function __construct($request, $response)
    {
        $this->shopId = $request->shopId;
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

        $token = $this->session->get($field, '');
        if ($token) {
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

        $field = trim((string)reset($field));
        return $this->session->get($field, '') == $token;
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

    /**
     * 检测上传图片
     */
    public function chkUploadImage(array $fileInfo, string $prefix): array
    {
        if (empty($fileInfo)) {
            return ['status' => 'fail', 'msg' => '上传图片不存在'];
        }

        if ($fileInfo['error'] > 0) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 错误'];
        }

        // Max 2MB
        if ($fileInfo['size'] > 2097152) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 过大'];
        }

        $imageName = md5($fileInfo['name']) . '_d_d';
        switch (strtolower($fileInfo['type'])) {
            case 'image/jpeg':
            case 'image/jpg':
                $imageName .= '.jpg';
                break;
            case 'image/png':
                $imageName .= '.png';
                break;
            default:
                return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 类型错误'];
        }

        if (!is_uploaded_file($fileInfo['tmp_name'])) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 非法'];
        }

        if (chk_file_security_is_risk($fileInfo['tmp_name'])) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 无效'];
        }

        $fileClass = 'image';
        $localPath = ROOT_DIR . 'upload/' . $fileClass . '/';
        $imageFile = $localPath . $prefix;
        if (stripos($prefix, '/logo') === false) {
            $imageFile .= date('/Ymd/');
        }
        if (!is_dir($imageFile) && !mkdir($imageFile, 0700, true)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 路径无效'];
        }

        $imageFile .= $imageName;
        if (!move_uploaded_file($fileInfo['tmp_name'], $imageFile)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 迁移失败'];
        }

        if (strpos($prefix, '/prod_img/') !== false) {
            $imgSrc = (new OssHelper($this->shopId))->putObjectForProductImage($imageFile, $localPath);
        } else {
            $imgSrc = (new OssHelper($this->shopId))->putObjectForImage($imageFile, $localPath);
        }
        if (empty($imgSrc)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 上传失败'];
        }

        return [$fileClass, $localPath, $imageFile, $imgSrc];
    }

}
