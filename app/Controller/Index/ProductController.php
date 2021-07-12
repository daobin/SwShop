<?php
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;
use App\Helper\DbHelper;

class ProductController extends Controller
{
    public function category(){
        $data = ['cate_name' => '大件家居'];
        return $this->render($data);
    }

    public function detail(){

    }
}
