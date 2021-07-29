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

    public function __construct($shopId)
    {
        $ossCfgs = (new ConfigBiz())->getConfigListByGroup($shopId, 'oss');
        $ossCfgs = array_column($ossCfgs, 'config_value', 'config_key');

        $this->ossBucket = $ossCfgs['OSS_BUCKET'];

        try{
            $this->ossClient = new OssClient($ossCfgs['OSS_ACCESS_KEY_ID'], $ossCfgs['OSS_ACCESS_KEY_SECRET'], $ossCfgs['OSS_ENDPOINT'])
        }catch (OssException $e){
            throw new OssException('OSS Client Invalid: ' . $e->getMessage());
        };
    }

    public static function putObjectForProductImage($sku, $sort, $imgeFile)
    {
        try {
            // 原始图片保存在本地，但不对外提供访问
            // 生成缩图上传至 OSS，用以对外提供访问
            $object = 'swshop/prod_image/' . $filename;

            $this->ossClient->putObject($this->ossBucket, $object, file_get_contents($imgeFile));
        } catch (OssException $e) {
            throw new OssException('OSS Put Invalid: ' . $e->getMessage());
        }
    }
}
