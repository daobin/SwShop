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

    public function deleteCustomerCart(int $shopId, int $customerId): bool
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return true;
        }

        $cartSkuList = $this->getCartListByCustomerId($shopId, $customerId);
        if (empty($cartSkuList)) {
            return true;
        }

        $cnt = $this->dbHelper->table('shopping_cart')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->delete();

        return $cnt > 0 ? true : false;
    }

    public function deleteCartSku(int $shopId, int $customerId, string $sku): bool
    {
        if ($shopId <= 0 || $customerId <= 0 || $sku === '') {
            return true;
        }

        $cartSkuInfo = $this->getCartListByCustomerId($shopId, $customerId, $sku);
        if (empty($cartSkuInfo)) {
            return true;
        }

        $cnt = $this->dbHelper->table('shopping_cart')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId, 'sku' => $sku])->delete();

        return $cnt > 0 ? true : false;
    }

    public function deleteCartSkuArr(int $shopId, int $customerId, array $skuArr): bool
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($skuArr)) {
            return true;
        }

        try {
            $this->dbHelper->table('shopping_cart')->where(
                ['shop_id' => $shopId, 'customer_id' => $customerId, 'sku' => ['in', $skuArr]])
                ->delete();

        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }

    public function getCartListByCustomerId(int $shopId, int $customerId, ?string $sku = null): array
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

        $cartList = $this->dbHelper->table('shopping_cart')->where($where)->fields($fields)->select();
        $cartList = $cartList ? array_column($cartList, null, 'sku') : [];
        return $cartList;
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

    public function updateCart(int $shopId, int $customerId, array $cartList): array
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return $cartList;
        }

        $time = time();
        $customerCartList = $this->getCartListByCustomerId($shopId, $customerId);

        if (!empty($cartList)) {
            foreach ($cartList as $sku => $cartInfo) {
                $saveData = [
                    'shop_id' => $shopId,
                    'customer_id' => $customerId,
                    'product_id' => (int)$cartInfo['product_id'],
                    'sku' => $sku,
                    'qty' => (int)$cartInfo['qty'],
                    'price' => (float)$cartInfo['price'],
                    'updated_at' => $time
                ];

                if (empty($customerCartList[$sku])) {
                    $saveData['created_at'] = $time;
                    $this->dbHelper->table('shopping_cart')->insert($saveData);
                } else {
                    $this->dbHelper->table('shopping_cart')->where(
                        ['shop_id' => $shopId, 'shopping_cart_id' => $customerCartList[$sku]['shopping_cart_id']])
                        ->update($saveData);

                    unset($customerCartList[$sku]);
                }
            }
        }

        if (!empty($customerCartList)) {
            foreach ($customerCartList as $sku => $carInfo) {
                $cartList[$sku] = [
                    'product_id' => (int)$carInfo['product_id'],
                    'sku' => $sku,
                    'qty' => (int)$carInfo['qty'],
                    'price' => (float)$carInfo['price']
                ];
            }
        }

        return $cartList;
    }
}
