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
use Intervention\Image\ImageManagerStatic;
use OSS\Core\OssException;
use OSS\OssClient;

class OssHelper
{
    private $ossCfgs;
    public $accessHost;

    public function __construct($shopId)
    {
        $this->ossCfgs = (new ConfigBiz())->getConfigListByGroup($shopId, 'oss');
        $this->ossCfgs = array_column($this->ossCfgs, 'config_value', 'config_key');

        $this->accessHost = str_ireplace(['https://', 'http://'], '', $this->ossCfgs['OSS_ENDPOINT']);
        $this->accessHost = 'https://' . $this->ossCfgs['OSS_BUCKET'] . '.' . trim($this->accessHost, '/') . '/';
    }

    public function putObjectForImage(string $imageFile, string $localPath): string
    {
        if (!is_file($imageFile)) {
            return '';
        }

        try {
            // 原始图片保存在本地，但不对外提供访问
            // 原始图片上传至 OSS，用以对外提供访问
            $object = str_ireplace($localPath, '', $imageFile);
            $object = trim($object, '/');

            $ossClient = new OssClient($this->ossCfgs['OSS_ACCESS_KEY_ID'], $this->ossCfgs['OSS_ACCESS_KEY_SECRET'], $this->ossCfgs['OSS_ENDPOINT']);
            $ossClient->putObject($this->ossCfgs['OSS_BUCKET'], $object, file_get_contents($imageFile));

        } catch (OssException $e) {
            print_r('OSS Put Image Invalid: ' . $e->getMessage());
        }

        return $this->accessHost . $object . '?' . time();
    }

    public function putObjectForProductImage(string $imageFile, string $localPath): string
    {
        if (!is_file($imageFile)) {
            return '';
        }

        try {
            // 原始图片只保存在本地，但不对外提供访问
            // 生成缩图上传至 OSS，用以对外提供访问
            $object = str_ireplace($localPath, '', $imageFile);
            $object = trim($object, '/');

            $imgSizeArr = ConfigHelper::get('product.image_size', []);
            if (empty($imgSizeArr)) {
                return '';
            }

            $thumbFileArr = [];
            $basename = basename($imageFile);
            foreach ($imgSizeArr as $imgSize) {
                $thumbFile = $localPath . 'thumb_' . $imgSize . '_' . $basename;
                $imgRes = ImageManagerStatic::make($imageFile)->resize($imgSize, $imgSize);
                $imgRes->save($thumbFile);

                if (is_file($thumbFile)) {
                    $thumbFileArr[] = $thumbFile;
                    $thumbObject = str_replace('_d_d', '_' . $imgSize . '_' . $imgSize, $object);

                    $ossClient = new OssClient($this->ossCfgs['OSS_ACCESS_KEY_ID'], $this->ossCfgs['OSS_ACCESS_KEY_SECRET'], $this->ossCfgs['OSS_ENDPOINT']);
                    $ossClient->putObject($this->ossCfgs['OSS_BUCKET'], $thumbObject, file_get_contents($thumbFile));
                }
            }

        } catch (OssException $e) {
            print_r('OSS Put Prod Image Invalid: ' . $e->getMessage());
        }

        if (empty($thumbFileArr)) {
            return '';
        }

        // 删除临时文件
        foreach ($thumbFileArr as $thumbFile) {
            if (is_file($thumbFile)) {
                unlink($thumbFile);
            }
        }

        $imgSize = reset($imgSizeArr);
        return $this->accessHost . str_replace('_d_d', '_' . $imgSize . '_' . $imgSize, $object) . '?' . time();
    }
}
