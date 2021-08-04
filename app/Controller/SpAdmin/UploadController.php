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
    public function prodImage()
    {
        $page = (int)$this->get('page', 1);
        $page = $page > 1 ? $page : 1;
        $pageSize = 60;
        $folder = $this->getProdImgPrefix('get', true);

        $data = [];
        $imageList = (new UploadBiz())->getUploadListByFolder($this->shopId, $folder, $page, $pageSize);
        if (!empty($imageList)) {
            $imgSizeArr = ConfigHelper::get('product.image_size', []);
            $imgSize = $imgSizeArr ? reset($imgSizeArr) : '';

            $ossAccessHost = (new OssHelper($this->shopId))->accessHost;
            foreach ($imageList as $imageInfo) {
                $data[] = [
                    'name' => $imageInfo['origin_name'],
                    'src' => $ossAccessHost . str_replace('_d_', '_' . $imgSize . '_', $imageInfo['oss_object'])
                ];
            }
        }

        return [
            'data' => $data,
            'pages' => 1
        ];
    }

    public function uploadProdImage()
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

        $time = time();
        $imageName = md5($fileInfo['name']) . '_d_' . $time;
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
        $prefix = $this->getProdImgPrefix('post');
        $imageFile = $localPath . $prefix . '/';
        if (!is_dir($imageFile) && !mkdir($imageFile, 0700, true)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 路径无效'];
        }

        $imageFile .= $imageName;
        if (!move_uploaded_file($fileInfo['tmp_name'], $imageFile)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 迁移失败'];
        }

        $data = [
            'shop_id' => $this->shopId,
            'origin_name' => $fileInfo['name'],
            'oss_object' => $prefix . '/' . $imageName,
            'file_class' => $fileClass,
            'folder' => $this->getProdImgPrefix('post', true),
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ];
        if (!(new UploadBiz())->saveUploadInfo($data)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 保存失败'];
        }

        $imgSrc = (new OssHelper($this->shopId))->putObjectForProductImage($imageFile, $localPath);
        if (empty($imgSrc)) {
            return ['status' => 'fail', 'msg' => '上传图片 [' . $fileInfo['name'] . '] 上传失败'];
        }

        return [
            'status' => 'success',
            'name' => $fileInfo['name'],
            'src' => $imgSrc
        ];
    }

    private function getProdImgPrefix(string $method = 'get', bool $getFolder = false): string
    {
        $prefix = $this->get('folder');
        if ($method == 'post') {
            $prefix = $this->post('folder');
        }

        $prefix = preg_replace('/[^a-z\d_]+/', '', trim($prefix));
        $prefix = trim($prefix, '/');
        $prefix = empty($prefix) ? 'def' : $prefix;
        if ($getFolder) {
            return $prefix;
        }

        $prefix = 'sp_' . $this->shopId . '/prod_img/' . $prefix;
        return trim($prefix, '/');
    }
}
