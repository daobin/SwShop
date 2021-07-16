<?php
/**
 * 店铺商品页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;

class ProductController extends Controller
{
    public function category(){
        $data = ['cate_name' => '大件家居'];
        return $this->render($data);
    }

    public function detail(){
        return '<h1>Product Detail\'s Page</h1>';
    }
}
