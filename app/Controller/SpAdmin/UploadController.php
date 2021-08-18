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
        if (empty($fileInfo)) {
            return ['status' => 'fail', 'msg' => '上传图片不存在'];
        }

        if ($fileInfo['error'] > 0) {
            print_r($fileInfo);
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
        $prefix = $this->getImgPrefix('post');
        $imageFile = $localPath . $prefix . '/';
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

        $time = time();
        $data = [
            'shop_id' => $this->shopId,
            'origin_name' => $fileInfo['name'],
            'oss_object' => $prefix . '/' . $imageName,
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
