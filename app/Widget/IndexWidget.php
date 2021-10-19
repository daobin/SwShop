<?php
declare(strict_types=1);

namespace App\Widget;

use App\Biz\ConfigBiz;
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
            'cate_list' => $cateList,
            'keywords' => $params['keywords'] ?? '',
            'website_name' => $params['website_name'] ?? '',
            'website_logo' => $params['website_logo'] ?? ''
        ];

        $data['tkd_title'] = $params['tkd_title'] ?? $data['website_name'];
        $data['tkd_keywords'] = $params['tkd_keywords'] ?? $data['website_name'];
        $data['tkd_description'] = $params['tkd_description'] ?? $data['website_name'];

        if (!empty($data['shopping_nav_img'])) {
            return $this->render('header_shopping', $data);
        }

        return $this->render('header', $data);
    }

    public function footer($params = [])
    {
        $year = date('Y');
        if ($year > 2021) {
            $year = '2021 - ' . $year;
        } else {
            $year = 2021;
        }

        $data = [
            'website_name' => $params['website_name'] ?? '',
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
            'year' => $year
        ];
        return $this->render('footer', $data);
    }
}
