<?php
/**
 * 店铺商品页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;
use App\Helper\DbHelper;
use App\Helper\OssHelper;

class ProductController extends Controller
{
    public function category(){
        OssHelper::putObjectForCss('index.css', 'h1{color:blue;}');
        $data = ['cate_name' => '大件家居'];
        return $this->render($data);
    }

    public function detail(){
        return '<h1>Product Detail\'s Page</h1>';
    }
}
