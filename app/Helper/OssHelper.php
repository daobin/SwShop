<?php
/**
 * 阿里 OSS 对接助手
 * User: dao bin
 * Date: 2021/7/12
 * Time: 15:35
 */
declare(strict_types=1);

namespace App\Helper;

use OSS\Core\OssException;
use OSS\OssClient;

class OssHelper
{
    /**
     * @var OssClient
     */
    private static $ossClient;
    private static $bucket;

    private static function initOssClient()
    {
        if (empty(self::$ossClient)) {
            try {
                self::$bucket = ConfigHelper::get('oss.bucket');

                $accessKeyId = ConfigHelper::get('oss.access_key_id');
                $accessKeySecret = ConfigHelper::get('oss.access_key_secret');
                $endpoint = ConfigHelper::get('oss.endpoint');
                self::$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            } catch (OssException $e) {
                throw new OssException('OSS Invalid: ' . $e->getMessage());
            }
        }
    }

    public static function putObjectForCss($filename, $content)
    {
        self::initOssClient();

        try {
            $object = 'swshop/static/' . $filename;

            self::$ossClient->putObject(self::$bucket, $object, $content);
        } catch (OssException $e) {
            throw new OssException('OSS Put Invalid: ' . $e->getMessage());
        }
    }

    public static function putObjectForProductImage($sku, $sort, $imgeFile)
    {
        self::initOssClient();

        try {
            // 原始图片保存在本地，但不对外提供访问
            // 生成缩图上传至 OSS，用以对外提供访问
            $object = 'swshop/cache_image/' . $filename;

            self::$ossClient->putObject(self::$bucket, $object, $content);
        } catch (OssException $e) {
            throw new OssException('OSS Put Invalid: ' . $e->getMessage());
        }
    }
}
