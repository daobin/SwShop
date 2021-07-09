<?php
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;
use App\Helper\DbHelper;

class ProductController extends Controller
{
    public function category(){
        $shopList = DbHelper::connection()->table('sys_shop')
            ->where(['created_at' => 0])->select();
        $data = ['cate_name' => '大件家居', 'shop_list' => $shopList];
        return $this->render($data);
    }

    public function detail(){

    }
}
