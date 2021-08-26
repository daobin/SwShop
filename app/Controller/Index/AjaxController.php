<?php
/**
 * 前台异步处理
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\CustomerBiz;
use App\Biz\ProductBiz;
use App\Biz\ShoppingBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;

class AjaxController extends Controller
{
    public function loginProcess()
    {
        $email = $this->post('email');
        $password = $this->post('password');
        $customer = (new CustomerBiz())->getCustomerByEmail($this->shopId, $email);
        if (empty($customer)) {
            return ['status' => 'fail', 'msg' => 'Invalid email or password'];
        }
        if (!password_verify($password, $customer['password'])) {
            return ['status' => 'fail', 'msg' => 'Invalid email or password'];
        }

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_customer_info', json_encode($customer));
        $this->session->remove('IDXlogin');

        (new ShoppingBiz())->updateCart($this->shopId, $customer['customer_id'], $this->cartList);

        $loginTo = $this->session->get('login_to', '/account.html');

        return ['status' => 'success', 'url' => $loginTo];
    }

    public function registerProcess()
    {
        $token = $this->post('hash_tk');
        $idempotentField = 'idempotent_register';
        if (empty($this->session->get($idempotentField))) {
            $this->session->set($idempotentField, $token);
        } else {
            return ['status' => 'fail'];
        }

        $time = time();
        $register = (new CustomerBiz())->register([
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'password2' => $this->post('password2'),
            'shop_id' => $this->shopId,
            'host_from' => $this->host,
            'device_from' => $this->deviceFrom,
            'ip_number' => $this->ip,
            'ip_country_iso_code_2' => $this->ipCountryIsoCode2,
            'registered_at' => $time,
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ]);

        $this->session->remove($idempotentField);
        if ($register['status'] === 'success') {
            $this->session->renameKey($this->request->domain);
            $this->session->set('sp_customer_info', json_encode($register['customer_info']));
            $this->session->remove('IDXregister');
            $register['url'] = $this->session->get('login_to', '/account.html');

            (new ShoppingBiz())->updateCart($this->shopId, $register['customer_info']['customer_id'], $this->cartList);
        }

        return $register;
    }

    public function addToCart()
    {
        $token = $this->post('hash_tk');
        $idempotentField = 'idempotent_add_to_cart';
        if (empty($this->session->get($idempotentField))) {
            $this->session->set($idempotentField, $token);
        } else {
            return ['status' => 'fail'];
        }

        $sku = $this->post('sku', 'trim,strtoupper');
        $prodQty = $this->post('prod_qty', 0);
        $prodQty = (int)$prodQty;
        if ($prodQty <= 0) {
            return ['status' => 'fail', 'msg' => '商品数量无效'];
        }

        $warehouse = 'US';
        $skuInfo = (new ProductBiz())->getSkuQtyPriceListBySkuArr($this->shopId, [$sku]);
        $skuInfo = $skuInfo[$sku][$warehouse] ?? [];
        if (empty($skuInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        if ((int)$skuInfo['qty'] <= 0) {
            return ['status' => 'fail', 'msg' => '商品已缺货'];
        }

        $cartData = [
            'product_id' => (int)$skuInfo['product_id'],
            'sku' => $sku,
            'qty' => $prodQty,
            'price' => (float)$skuInfo['price']
        ];

        $cartQty = $prodQty;
        if (empty($this->cartList)) {
            $this->cartList[] = $cartData;
        } else {
            foreach ($this->cartList as $idx => $cartInfo) {
                if ($sku == $cartInfo['sku']) {
                    $this->cartList[$idx]['qty'] = $prodQty + $cartInfo['qty'];
                    $this->cartList[$idx]['price'] = $cartData['price'];
                } else {
                    $cartQty += $cartInfo['qty'];
                }
            }
        }
        $this->session->set('cart_list', json_encode($this->cartList));

        $this->session->remove($idempotentField);

        return [
            'status' => 'success',
            'cart_qty' => $cartQty,
            'add_qty' => $prodQty,
            'add_price' => format_price($cartData['price'], $this->currency, $prodQty, true)
        ];
    }

    public function updateCartProduct()
    {
        $token = $this->post('hash_tk');
        $idempotentField = 'idempotent_up_cart_prod';
        if (empty($this->session->get($idempotentField))) {
            $this->session->set($idempotentField, $token);
        } else {
            return ['status' => 'fail'];
        }

        $sku = $this->post('sku', 'trim,strtoupper');
        $prodQty = $this->post('prod_qty', 0);
        $prodQty = (int)$prodQty;
        if ($prodQty <= 0) {
            return ['status' => 'fail', 'msg' => '商品数量无效'];
        }

        $warehouse = 'US';
        $skuInfo = (new ProductBiz())->getSkuQtyPriceListBySkuArr($this->shopId, [$sku]);
        $skuInfo = $skuInfo[$sku][$warehouse] ?? [];
        if (empty($skuInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        if ((int)$skuInfo['qty'] <= 0) {
            return ['status' => 'fail', 'msg' => '商品已缺货'];
        }

        $cartData = [
            'shop_id' => $this->shopId,
            'product_id' => (int)$skuInfo['product_id'],
            'sku' => $sku,
            'qty' => $prodQty,
            'price' => (float)$skuInfo['price']
        ];

        $cartPrice = (float)format_price($cartData['price'], $this->currency, $prodQty);
        $cartQty = $prodQty;
        $exist = false;
        if (!empty($this->cartList)) {
            foreach ($this->cartList as $idx => $cartInfo) {
                if ($sku == $cartInfo['sku']) {
                    $exist = true;
                    $this->cartList[$idx]['qty'] = $prodQty;
                    $this->cartList[$idx]['price'] = $cartData['price'];
                } else {
                    $cartPrice += (float)format_price($cartInfo['price'], $this->currency, $cartInfo['qty']);
                    $cartQty += $cartInfo['qty'];
                }
            }
        }
        if (!$exist) {
            $this->cartList[] = $cartData;
        }
        $this->session->set('cart_list', json_encode($this->cartList));

        $this->session->remove($idempotentField);

        return [
            'status' => 'success',
            'cart_qty' => $cartQty,
            'cart_price' => format_price_total($cartPrice, $this->currency)
        ];
    }
}
