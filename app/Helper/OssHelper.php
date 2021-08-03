<?php
/**
 * 阿里 OSS 对接助手
 * User: dao bin
 * Date: 2021/7/12
 * Time: 15:35
 */
declare(strict_types=1);

namespace App\Helper;

use App\Biz\ConfigBiz;
use OSS\Core\OssException;
use OSS\OssClient;

class OssHelper
{
    private $ossClient;
    private $ossBucket;
    public $accessHost;

    public function __construct($shopId)
    {
        $ossCfgs = (new ConfigBiz())->getConfigListByGroup($shopId, 'oss');
        $ossCfgs = array_column($ossCfgs, 'config_value', 'config_key');

        $this->ossBucket = $ossCfgs['OSS_BUCKET'];
        $this->accessHost = str_ireplace(['https://', 'http://'], '', $ossCfgs['OSS_ENDPOINT']);
        $this->accessHost = 'https://' . $this->ossBucket . '.' . trim($this->accessHost, '/') . '/';

        try {
            $this->ossClient = new OssClient($ossCfgs['OSS_ACCESS_KEY_ID'], $ossCfgs['OSS_ACCESS_KEY_SECRET'], $ossCfgs['OSS_ENDPOINT']);
        } catch (OssException $e) {
            throw new OssException('OSS Client Invalid: ' . $e->getMessage());
        };
    }

    public function getObjectForProductImage($prefix)
    {
        $prefix = trim($prefix, '/');
        $prefix = $prefix == '' ? '' : $prefix . '/';

        $options = [
            'prefix' => $prefix,
            'delimiter' => '/',
            'max-keys' => 60
        ];

        try {
            $objectList = $this->ossClient->listObjects($this->ossBucket, $options);
        } catch (OssException $e) {
            throw new OssException('OSS Get Prod Image Invalid: ' . $e->getMessage());
        }

        $objectList = $objectList->getObjectList();
        if (empty($objectList)) {
            return [];
        }

        $imgList = [];
        foreach ($objectList as $object) {
            $imgList[] = [
                'src' => $this->accessHost . $object->getKey() . '?' . $object->getLastModified(),
                'name' => $object->getKey()
            ];
        }
        return $imgList;
    }

    public function putObjectForProductImage($sku, $sort, $imgeFile)
    {
        try {
            // 原始图片保存在本地，但不对外提供访问
            // 生成缩图上传至 OSS，用以对外提供访问
            $object = 'swshop/prod_image/' . $filename;

            $this->ossClient->putObject($this->ossBucket, $object, file_get_contents($imgeFile));
        } catch (OssException $e) {
            throw new OssException('OSS Put Prod Image Invalid: ' . $e->getMessage());
        }
    }
}
