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
    public function category()
    {
        $data = [];

        return $this->render($data);
    }

    public function detail()
    {
        $data = [
            'device' => $this->device
        ];

        return $this->render($data);
    }
}
