<?php
/**
 * 文件上传控制
 * User: dao bin
 * Date: 2021/7/29
 * Time: 9:56
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

class UploadController
{
    public function index(){
        $imageList = [
            [
                'src' => 'https://www.glarrymusic.com/thumb_image/product/s/spug/spug17000205/spug17000205_400_400.jpg?20200201221117',
                'name' => 'SKU001.jpg'
            ],
            [
                'src' => 'https://www.glarrymusic.com/thumb_image/product/s/spug/spug17000205/spug17000205_400_400.jpg?20200201221117',
                'name' => 'SKU001.jpg'
            ],
            [
                'src' => 'https://www.glarrymusic.com/thumb_image/product/s/spug/spug17000205/spug17000205_400_400.jpg?20200201221117',
                'name' => 'SKU001.jpg'
            ],
            [
                'src' => 'https://www.glarrymusic.com/thumb_image/product/s/spug/spug17000205/spug17000205_400_400.jpg?20200201221117',
                'name' => 'SKU001.jpg'
            ],
        ];

        return [
            'data' => $imageList,
            'pages' => 3
        ];
    }

    public function upload(){

    }
}
