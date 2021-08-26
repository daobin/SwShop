<?php
/**
 * 购物流程业务
 * User: dao bin
 * Date: 2021/8/19
 * Time: 16:20
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ShoppingBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getCartByCustomerId(int $shopId, int $customerId, ?string $sku = null): array
    {
        if ($shopId <= 0 || $customerId <= 0 || $sku !== null && empty($sku)) {
            return [];
        }

        $fields = ['shopping_cart_id', 'customer_id', 'product_id', 'sku', 'qty', 'price'];
        $where = ['shop_id' => $shopId, 'customer_id' => $customerId];
        if ($sku !== null) {
            $where['sku'] = $sku;
            return $this->dbHelper->table('shopping_cart')->where($where)->fields($fields)->find();
        }

        return $this->dbHelper->table('shopping_cart')->where($where)->fields($fields)->select();
    }

    public function getCartQtyByCustomerId(int $shopId, int $customerId): int
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return 0;
        }

        $cartQty = $this->dbHelper->table('shopping_cart')
            ->where(['shop_id' => $shopId, 'customer_id' => $customerId])->sum(['qty'])->find();

        $cartQty = $cartQty ? reset($cartQty) : 0;
        return (int)$cartQty;
    }

    public function updateCart(int $shopId, int $customerId, array $cartList): int
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($cartList)) {
            return 0;
        }

        $time = time();
        $customerCartList = $this->getCartByCustomerId($shopId, $customerId);
        $customerCartList = $customerCartList ? array_column($customerCartList, null, 'sku') : [];

        $cartList = array_column($cartList, null, 'sku');
        foreach ($cartList as $sku => $cartInfo) {
            $saveData = [
                'shop_id' => $shopId,
                'customer_id' => $customerId,
                'product_id' => (int)$cartInfo['product_id'],
                'sku' => $sku,
                'qty' => (int)$cartInfo['qty'],
                'price' => (float)$cartInfo['price'],
                'created_at' => $time,
                'updated_at' => $time
            ];

            if (empty($customerCartList[$sku])) {
                $this->dbHelper->table('shopping_cart')->insert($saveData);
            } else {
                unset($saveData['created_at']);
                $this->dbHelper->table('shopping_cart')->where(
                    ['shop_id' => $shopId, 'shopping_cart_id' => $customerCartList[$sku]['shopping_cart_id']])
                    ->update($saveData);
            }
        }

        return $this->getCartQtyByCustomerId($shopId, $customerId);
    }
}
