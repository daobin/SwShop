<?php
/**
 * 文件上传控制
 * User: dao bin
 * Date: 2021/7/29
 * Time: 9:56
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\OssHelper;

class UploadController extends Controller
{
    public function prodImage()
    {
        return [
            'data' => (new OssHelper($this->shopId))->getObjectForProductImage($this->getProdImgPrefix()),
            'pages' => 1
        ];
    }

    public function uploadProdImage()
    {

    }

    private function getProdImgPrefix()
    {
        $prefix = $this->get('folder');

        return $this->shopId . '/prod_img/' . trim($prefix, '/');
    }
}
