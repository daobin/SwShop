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
    public static function putObjectForCss($filename, $content)
    {
        $object = 'swshop/static/' . $filename;
        $ossCfgs = ConfigHelper::get('oss');

        try {
            $ossClient = new OssClient($ossCfgs['oss_access_key_id'], $ossCfgs['oss_access_key_secret'], $ossCfgs['oss_endpoint']);
            $ossClient->putObject($ossCfgs['oss_bucket'], $object, $content);
        } catch (OssException $e) {
            throw new OssException('OSS Put Invalid: ' . $e->getMessage());
        }
    }

    public static function putObjectForProductImage($sku, $sort, $imgeFile)
    {
        try {
            // 原始图片保存在本地，但不对外提供访问
            // 生成缩图上传至 OSS，用以对外提供访问
            $object = 'swshop/cache_image/' . $filename;
            $ossCfgs = ConfigHelper::get('oss');

            $ossClient = new OssClient($ossCfgs['oss_access_key_id'], $ossCfgs['oss_access_key_secret'], $ossCfgs['oss_endpoint']);
            $ossClient->putObject($ossCfgs['oss_bucket'], $object, file_get_contents($imgeFile));
        } catch (OssException $e) {
            throw new OssException('OSS Put Invalid: ' . $e->getMessage());
        }
    }
}
