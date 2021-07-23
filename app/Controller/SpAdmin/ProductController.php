<?php
/**
 * 商品管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\DbHelper;

class ProductController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $prodList = [];

            return [
                'code' => 0,
                'count' => count($prodList),
                'data' => $prodList
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        return $this->render();
    }
}
