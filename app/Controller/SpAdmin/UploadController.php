<?php
/**
 * 文件上传控制
 * User: dao bin
 * Date: 2021/7/29
 * Time: 9:56
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\UploadBiz;
use App\Controller\Controller;
use App\Helper\ConfigHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class UploadController extends Controller
{
    public function image()
    {
        $page = (int)$this->get('page', 1);
        $page = $page > 1 ? $page : 1;
        $pageSize = 60;
        $folder = $this->getImgPrefix('get', true);

        $data = [];
        $imageList = (new UploadBiz())->getUploadListByFolder($this->shopId, $folder, $page, $pageSize);
        if (!empty($imageList)) {
            $ossAccessHost = (new OssHelper($this->shopId))->accessHost;
            foreach ($imageList as $imageInfo) {
                if (strpos($imageInfo['oss_object'], '/prod_img/') !== false) {
                    $imageInfo['oss_object'] = str_replace('_d_d', '_300_300', $imageInfo['oss_object']);
                }

                $data[] = [
                    'name' => $imageInfo['origin_name'],
                    'src' => $ossAccessHost . $imageInfo['oss_object'] . '?' . $imageInfo['updated_at']
                ];
            }
        }

        return [
            'data' => $data,
            'pages' => 1
        ];
    }

    public function uploadImage()
    {
        $fileInfo = $this->request->files['file'] ?? [];
        $checked = (new SafeHelper($this->request, $this->response))->chkUploadImage($fileInfo, $this->getImgPrefix('post'));
        if (isset($checked['status']) && $checked['status'] == 'fail') {
            return $checked;
        }

        list($fileClass, $localPath, $imageFile, $imgSrc) = $checked;

        $time = time();
        $data = [
            'shop_id' => $this->shopId,
            'origin_name' => $fileInfo['name'],
            'oss_object' => str_replace($localPath, '', $imageFile),
            'file_class' => $fileClass,
            'folder' => $this->getImgPrefix('post', true),
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ];
        if (!(new UploadBiz())->saveUploadInfo($data)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 保存失败'];
        }

        return [
            'status' => 'success',
            'name' => $fileInfo['name'],
            'src' => $imgSrc
        ];
    }

    private function getImgPrefix(string $method = 'get', bool $getFolder = false): string
    {
        $prefix = $this->get('folder', 'trim,strtolower');
        if ($method == 'post') {
            $prefix = $this->post('folder');
        }

        $prefix = preg_replace('/[^a-z\d_]+/', '', $prefix);
        $prefix = trim($prefix, '/');
        $prefix = empty($prefix) ? 'def' : $prefix;
        if ($getFolder) {
            return $prefix;
        }

        if (substr($prefix, 0, 3) == 'sku') {
            $prefix = 'prod_img/' . $prefix;
        }

        $prefix = 'sp_' . $this->shopId . '/' . $prefix;
        return trim($prefix, '/');
    }
}
