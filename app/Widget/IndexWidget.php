<?php
declare(strict_types=1);

namespace App\Widget;

use App\Biz\ProductBiz;

class IndexWidget extends Widget
{
    public function header($params = [])
    {
        $shopId = $params['shop_id'] ?? 0;
        $cateList = (new ProductBiz())->getCategoryTree((int)$shopId);

        $data = [
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
            'customer_id' => $params['customer_id'] ?? 0,
            'cart_qty' => $params['cart_qty'] ?? 0,
            'shopping_nav_img' => $params['shopping_nav_img'] ?? '',
            'cate_list' => $cateList
        ];

        if(!empty($data['shopping_nav_img'])){
            return $this->render('header_shopping', $data);
        }

        return $this->render('header', $data);
    }

    public function footer($params = [])
    {
        $data = [
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
        ];
        return $this->render('footer', $data);
    }
}
