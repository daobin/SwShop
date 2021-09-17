<?php
/**
 * 用户订单业务
 * User: dao bin
 * Date: 2021/8/31
 * Time: 9:46
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class OrderBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getOrderForTracking(int $shopId, string $email, string $orderNumber): array
    {
        if ($shopId <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($orderNumber)) {
            return [];
        }


        return [];
    }

    public function getOrderListByCustomerId(int $shopId, int $customerId): array
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return [];
        }

        return [];
    }

    public function getCustomerOrderById(int $shopId, int $customerId, int $orderId): array
    {
        if ($shopId <= 0 || $customerId <= 0 || $orderId <= 0) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'customer_id' => $customerId, 'order_id' => $orderId];
        $fields = [
            'order_id', 'order_number', 'customer_email', 'customer_name', 'order_status_id',
            'shipping_method', 'shipping_code', 'payment_method', 'payment_code', 'currency_code', 'currency_value',
            'order_total', 'default_currency_total', 'default_currency_code', 'created_by'
        ];

        $orderInfo = $this->dbHelper->table('order')->where($where)->fields($fields)->find();
        if(empty($orderInfo)){
            return [];
        }

        $fields = ['product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        $orderProductList = $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_order_id' => $orderId])->fields($fields)->select();
        if(empty($orderProductList)){
            return [];
        }

        return [];
    }
}
