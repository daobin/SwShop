<?php
/**
 * 店铺购物流程页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\AddressBiz;
use App\Biz\CurrencyBiz;
use App\Biz\CustomerBiz;
use App\Biz\OrderBiz;
use App\Biz\PaymentBiz;
use App\Biz\ProductBiz;
use App\Biz\ShippingBiz;
use App\Biz\ShoppingBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\OrderHelper;
use App\Helper\OssHelper;
use App\Helper\Payment\PaypalCcHelper;
use App\Helper\Payment\PaypalHelper;
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
        $soldOutSkuArr = [];
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
                if ((int)$this->cartList[$sku]['qty'] <= 0) {
                    $soldOutSkuArr[$sku] = $sku;
                }

                $prodPrice = $skuQtyPriceList[$sku]['price'] ?? 0;
                if ($cartInfo['price'] != $prodPrice) {
                    $modified = true;
                    $this->cartList[$sku]['price'] = (float)$prodPrice;
                }
                if ((float)$this->cartList[$sku]['price'] <= 0) {
                    $soldOutSkuArr[$sku] = $sku;
                }
            }

            if ($modified) {
                if ($this->customerId > 0) {
                    $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $this->customerId, $this->cartList);
                }
                if (empty($soldOutSkuArr)) {
                    $this->session->set('shopping_error', LanguageHelper::get('sold_out_for_shopping', $this->langCode));
                } else {
                    $this->session->set('shopping_error', LanguageHelper::get('modified_for_shopping', $this->langCode));
                }
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
            'error' => $this->session->get('shopping_error', ''),
            'sold_out_sku' => $soldOutSkuArr
        ];
        $this->session->set('shopping_error', '');

        return $this->render($data);
    }

    public function confirmation()
    {
        $orderError = (new OrderHelper($this->request, $this->response))->buildOrderSummaryForError($this->cartList, $this->customerId, $this->warehouseCode);
        if (isset($orderError['sold_out'])) {
            $this->session->set('shopping_error', LanguageHelper::get('sold_out_for_shopping', $this->langCode));
            return $this->response->redirect('/shopping/cart.html');
        }
        if (isset($orderError['qty_price_modified'])) {
            $this->session->set('shopping_error', LanguageHelper::get('modified_for_shopping', $this->langCode));
            return $this->response->redirect('/shopping/cart.html');
        }
        if (!empty($orderError)) {
            $this->session->set('shopping_error', LanguageHelper::get('invalid_order', $this->langCode));
            return $this->response->redirect('/shopping/cart.html');
        }

        $orderSummary = $this->session->get('order_summary', '{}');
        $orderSummary = json_decode($orderSummary, true);

        $shippingMethodList = (new ShippingBiz())->getShippingList($this->shopId);
        $paymentMethodList = (new PaymentBiz())->getPaymentList($this->shopId);

        $shippingAddressId = (int)$this->get('shipping_address', 0);
        $shippingAddressId = $shippingAddressId > 0 ? $shippingAddressId : $this->shippingAddressId;

        $data = [
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'shipping_address' => (new AddressBiz())->getAddressById($this->shopId, $this->customerId, $shippingAddressId),
            'order_summary' => $orderSummary,
            'shipping_list' => $shippingMethodList,
            'payment_list' => $paymentMethodList,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'payment'),
            'error' => $this->session->get('shopping_error', ''),
        ];
        $this->session->set('shopping_error', '');

        return $this->render($data);
    }

    public function payment()
    {
        // Check Shipping Address
        $addressId = (int)$this->post('address_id');
        $addressInfo = (new AddressBiz())->getAddressById($this->shopId, $this->customerId, $addressId);
        if (empty($addressInfo)) {
            $this->session->set('shopping_error', LanguageHelper::get('invalid_address', $this->langCode));
            return $this->response->redirect('/shopping/confirmation.html');
        }

        // Check Payment Method
        $paymentCode = $this->post('payment_method');
        $paymentInfo = (new PaymentBiz())->getPaymentByCode($this->shopId, $paymentCode);
        if (empty($paymentInfo)) {
            $this->session->set('shopping_error', LanguageHelper::get('invalid_payment', $this->langCode));
            return $this->response->redirect('/shopping/confirmation.html');
        }

        // Check Order
        $orderSummary = $this->session->get('order_summary', '{}');
        $orderSummary = json_decode($orderSummary, true);
        if (empty($orderSummary['prod_list']) || empty($orderSummary['warehouse_code']) || empty($orderSummary['totals'])) {
            $this->session->set('shopping_error', LanguageHelper::get('invalid_order', $this->langCode));
            return $this->response->redirect('/shopping/cart.html');
        }

        $skuArr = array_keys($orderSummary['prod_list']);
        $skuQtyPriceList = (new ProductBiz())->getSkuQtyPriceListBySkuArr($this->shopId, $skuArr, $this->warehouseCode);
        foreach ($orderSummary['prod_list'] as $sku => $prodInfo) {
            $prodQty = $skuQtyPriceList[$sku]['qty'] ?? 0;
            if ($prodInfo['qty'] > $prodQty) {
                return $this->response->redirect('/shopping/cart.html');
                $prodInfo['qty'] = (int)$prodQty;
            }
            if ((int)$prodInfo['qty'] <= 0) {
                return $this->response->redirect('/shopping/cart.html');
            }

            $prodPrice = $skuQtyPriceList[$sku]['price'] ?? 0;
            if ($prodInfo['price'] != $prodPrice) {
                return $this->response->redirect('/shopping/cart.html');
                $prodInfo['price'] = (float)$prodPrice;
            }
            if ((float)$prodInfo['price'] <= 0) {
                return $this->response->redirect('/shopping/cart.html');
            }
        }

        // 设置订单来源
        $orderSummary['ip'] = $this->ip;
        $orderSummary['ip_country_iso_code_2'] = $this->ipCountryIsoCode2;
        $orderSummary['host_from'] = $this->host;
        $orderSummary['device_from'] = $this->deviceFrom;
        $this->session->set('order_summary', json_encode($orderSummary));

        $payRes = [];
        $customerInfo = (new CustomerBiz($this->langCode))->getCustomerById($this->shopId, $this->customerId);;
        if ($paymentCode == 'paypal_cc') {
            $payRes = (new PaypalCcHelper($this->request, $this->response))->orderProcess($customerInfo, $addressInfo, $paymentInfo);
            if (isset($payRes['url'])) {
                return $this->response->redirect($payRes['url']);
            }
        } else if ($paymentCode == 'paypal') {
            $payRes = (new PaypalHelper($this->request, $this->response))->orderProcess($customerInfo, $addressInfo, $paymentInfo);
            if (isset($payRes['url'])) {
                return $this->response->redirect($payRes['url']);
            }
        }

        $msg = $payRes['msg'] ?? LanguageHelper::get('order_failed_tip', $this->langCode);
        $this->session->set('shopping_error', $msg);
        return $this->response->redirect('/shopping/confirmation.html');
    }

    public function paymentHandler()
    {
        $paymentCode = $this->get('pm');
        $token = $this->get('token');
        if (empty($token)) {
            return $this->response->redirect('/shopping/confirmation.html');
        }

        if ($paymentCode == 'paypal_cc') {
            $payRes = (new PaypalCcHelper($this->request, $this->response))->payProcess($token);
        } else if ($paymentCode == 'paypal') {
            $payRes = (new PaypalHelper($this->request, $this->response))->payProcess($token);
        }

        if (isset($payRes['url'])) {
            return $this->response->redirect($payRes['url']);
        }

        if (isset($payRes['msg'])) {
            $this->session->set('shopping_error', $payRes['msg']);
        }

        return $this->response->redirect('/shopping/confirmation.html');
    }

    public function paymentCancel()
    {
        $paymentCode = $this->get('pm');
        $token = $this->get('token');
        if (empty($token)) {
            return $this->response->redirect('/shopping/confirmation.html');
        }

        if ($paymentCode == 'paypal_cc') {
            $payRes = (new PaypalCcHelper($this->request, $this->response))->payCancel($token);
        } else if ($paymentCode == 'paypal') {
            $payRes = (new PaypalHelper($this->request, $this->response))->payCancel($token);
        }

        if (isset($payRes['url'])) {
            return $this->response->redirect($payRes['url']);
        }

        if (isset($payRes['msg'])) {
            $this->session->set('shopping_error', $payRes['msg']);
        }

        return $this->response->redirect('/shopping/confirmation.html');
    }

    public function success()
    {
        $orderBiz = new OrderBiz();

        $orderNumber = $this->session->get('success_order_number', '');
        $orderInfo = $orderBiz->getCustomerOrderByNumber($this->shopId, $this->customerId, $orderNumber);
        if (empty($orderInfo)) {
            $orderInfo = $orderBiz->getCustomerLastOne($this->shopId, $this->customerId);
        }

        $orderCurrency = (new CurrencyBiz())->getCurrencyByCode($this->shopId, $orderInfo['currency_code']);

        return $this->render(['order_info' => $orderInfo, 'order_currency' => $orderCurrency]);
    }
}
