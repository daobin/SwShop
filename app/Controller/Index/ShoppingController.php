<?php
/**
 * 店铺购物流程页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\ProductBiz;
use App\Biz\ShopBiz;
use App\Biz\ShoppingBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class ShoppingController extends Controller
{
    public function cart()
    {
        $skuQtyPriceList = [];
        $skuImgList = [];
        $prodNameList = [];
        $prodUrlList = [];
        $modified = false;
        if (!empty($this->cartList)) {
            $prodBiz = new ProductBiz();

            $cartSkuArr = array_keys($this->cartList);
            $skuQtyPriceList = $prodBiz->getSkuQtyPriceListBySkuArr($this->shopId, $cartSkuArr, $this->warehouseCode);
            $skuImgList = $prodBiz->getSkuImageListBySkuArr($this->shopId, $cartSkuArr, true);
            $prodIds = [];
            foreach ($this->cartList as $sku => $cartInfo) {
                $prodIds[$cartInfo['product_id']] = $cartInfo['product_id'];
                $prodQty = $skuQtyPriceList[$sku]['qty'] ?? 0;
                if ($cartInfo['qty'] > $prodQty) {
                    $modified = true;
                    $this->cartList[$sku]['qty'] = (int)$prodQty;
                }
                $prodPrice = $skuQtyPriceList[$sku]['price'] ?? 0;
                if ($cartInfo['price'] != $prodPrice) {
                    $modified = true;
                    $this->cartList[$sku]['price'] = (float)$prodPrice;
                }
            }

            if ($modified && $this->customerId > 0) {
                $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $this->customerId, $this->cartList);
            }

            $prodList = $prodBiz->getProductList(
                ['shop_id' => $this->shopId, 'language_code' => $this->langCode, 'product_ids' => $prodIds], [], 1, count($prodIds));
            $prodNameList = $prodList ? array_column($prodList, 'product_name', 'product_id') : [];
            $prodUrlList = $prodList ? array_column($prodList, 'product_url', 'product_id') : [];
        }
        $this->session->set('cart_list', json_encode($this->cartList));

        $data = [
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'cart_list' => $this->cartList,
            'sku_qty_price_list' => $skuQtyPriceList,
            'sku_img_list' => $skuImgList,
            'prod_name_list' => $prodNameList,
            'prod_url_list' => $prodUrlList,
            'cart_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'upCartProd'),
            'sold_out_text' => LanguageHelper::get('sold_out', $this->langCode),
            'modified' => $modified
        ];

        return $this->render($data);
    }

    public function confirm()
    {
        return $this->render();
    }

    public function payment()
    {

    }

    public function success()
    {
        return $this->render();
    }
}
