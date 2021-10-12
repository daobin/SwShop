<?php
/**
 * 订单助手
 * User: dao bin
 * Date: 2021/9/18
 * Time: 14:20
 */
declare(strict_types=1);

namespace App\Helper;

use App\Biz\ProductBiz;
use App\Biz\ShoppingBiz;

class OrderHelper
{
    private $shopId;
    private $langCode;
    private $currency;
    private $session;

    public function __construct($request, $response)
    {
        $this->shopId = $request->shopId;
        $this->langCode = $request->langCode ?? 'en';
        $this->currency = $request->currency ?? [];

        $this->session = new SessionHelper($request, $response);
    }

    public function buildOrderSummaryForError(array $cartList, int $customerId, string $warehouseCode): array
    {
        if (empty($cartList) || $customerId <= 0 || empty($warehouseCode)) {
            $this->session->set('order_summary', json_encode([]));
            return ['invalid_order' => 1];
        }

        $prodBiz = new ProductBiz();

        $cartSkuArr = array_keys($cartList);
        $skuQtyPriceList = $prodBiz->getSkuQtyPriceListBySkuArr($this->shopId, $cartSkuArr, $warehouseCode);
        $skuImgList = $prodBiz->getSkuImageListBySkuArr($this->shopId, $cartSkuArr, true);

        $prodIds = array_column($cartList, 'product_id', 'product_id');
        $prodIds = array_keys($prodIds);
        $prodList = $prodBiz->getProductList(['shop_id' => $this->shopId, 'language_code' => $this->langCode, 'product_ids' => $prodIds], [], 1, count($prodIds));
        $prodNameList = $prodList ? array_column($prodList, 'product_name', 'product_id') : [];

        $error = [];
        $subtotal = 0;

        foreach ($cartList as $sku => $cartInfo) {
            $prodId = (int)$cartInfo['product_id'];
            $cartList[$sku]['product_name'] = $prodNameList[$prodId] ?? '';
            $cartList[$sku]['product_img'] = $skuImgList[$sku] ?? '';

            $prodQty = $skuQtyPriceList[$sku]['qty'] ?? 0;
            if ($prodQty <= 0) {
                $error['sold_out'] = 1;
            } else if ($cartInfo['qty'] > $prodQty) {
                $error['qty_price_modified'] = 1;
                $cartList[$sku]['qty'] = (int)$prodQty;
            }

            $prodPrice = $skuQtyPriceList[$sku]['price'] ?? 0;
            if ($prodPrice <= 0) {
                $error['sold_out'] = 1;
            } else if ($cartInfo['price'] != $prodPrice) {
                $error['qty_price_modified'] = 1;
                $cartList[$sku]['price'] = (float)$prodPrice;
            }

            $cartList[$sku]['price_text'] = format_price($cartList[$sku]['price'], $this->currency, 1, true);
            $cartList[$sku]['total_text'] = format_price($cartList[$sku]['price'], $this->currency, $cartList[$sku]['qty'], true);

            $subtotal += (float)format_price($cartList[$sku]['price'], $this->currency, $cartList[$sku]['qty']);
        }

        if (isset($error['qty_price_modified'])) {
            $cartList = (new ShoppingBiz())->updateCart($this->shopId, $customerId, $cartList);
            $this->session->set('cart_list', json_encode($cartList));
        }

        $orderSummary = [
            'warehouse_code' => $warehouseCode,
            'prod_list' => $cartList
        ];

        $shippingFee = 0;

        $total = $subtotal + $shippingFee;

        $orderSummary['totals'] = [
            'subtotal' => [
                'title' => 'Subtotal',
                'price' => $subtotal,
                'text' => format_price_total($subtotal, $this->currency)
            ],
            'shipping' => [
                'title' => 'Shipping Fee',
                'price' => $shippingFee,
                'text' => format_price_total($shippingFee, $this->currency)
            ],
            'total' => [
                'title' => 'Total',
                'price' => $total,
                'text' => format_price_total($total, $this->currency)
            ]
        ];

        $this->session->set('order_summary', json_encode($orderSummary));

        unset($cartList, $orderSummary);
        return $error;
    }
}
