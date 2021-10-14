<?php
/**
 * 前台异步处理
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\AddressBiz;
use App\Biz\ConfigBiz;
use App\Biz\CustomerBiz;
use App\Biz\OrderBiz;
use App\Biz\ProductBiz;
use App\Biz\ShoppingBiz;
use App\Controller\Controller;
use App\Helper\EmailHelper;
use App\Helper\LanguageHelper;

class AjaxController extends Controller
{
    public function customerService()
    {
        $customerName = $this->post('your_name');
        $customerEmail = $this->post('your_email');
        $question = $this->post('your_question');
        $orderTime = $this->post('order_time', 0);
        $orderNumber = $this->post('order_number');

        if (empty($customerName)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_name', $this->langCode)];
        }

        $service = $this->post('service', '', 'trim,strtolower');
        if ($service === 'pre') {
            if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('email_invalid', $this->langCode)];
            }
        } else if ($service === 'after') {
            $customerInfo = $this->session->get('sp_customer_info');
            $customerInfo = $customerInfo ? json_decode($customerInfo, true) : [];
            if (empty($customerInfo['email'])) {
                $this->session->set('login_to', '/customer-service.html');
                return ['status' => 'fail', 'url' => '/login.html'];
            }

            if (empty($orderNumber)) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_order', $this->langCode)];
            }

            $customerEmail = $customerInfo['email'];
        }

        if (empty($question)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('your_question_invalid', $this->langCode)];
        }

        $csData = [
            'shop_id' => $this->shopId,
            'service_type' => $service,
            'customer_id' => $this->customerId,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'order_time' => $orderTime,
            'order_number' => $orderNumber,
            'question' => $question
        ];
        if ((new CustomerBiz($this->langCode))->submitCustomerService($csData) > 0) {
            \Swoole\Event::defer(function () use ($csData) {
                $csEmail = (new ConfigBiz())->getConfigByKey($this->shopId, 'CUSTOMER_SERVICE_EMAIL');
                $csEmail = $csEmail['config_value'] ?? '';

                $mailData = [
                    'template' => 'customer_service',
                    'to_address' => $csEmail,
                    'submission_time' => date('Y-m-d H:i:s'),
                    'customer_name' => $csData['customer_name'],
                    'customer_email' => $csData['customer_email'],
                    'customer_question' => $csData['question'],
                    'order_number' => $csData['order_number'],
                    'service_type' => $csData['service_type']
                ];
                (new EmailHelper($this->shopId, $this->host))->sendMail($mailData);
            });
            return ['status' => 'success', 'msg' => LanguageHelper::get('submitted_success', $this->langCode), 'reset' => 1];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('submitted_fail', $this->langCode)];
    }

    public function loginProcess()
    {
        $email = $this->post('email');
        $password = $this->post('password');
        $customerInfo = (new CustomerBiz($this->langCode))->getCustomerByEmail($this->shopId, $email);
        if (empty($customerInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('email_or_pwd_invalid', $this->langCode)];
        }
        if (!password_verify($password, $customerInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('email_or_pwd_invalid', $this->langCode)];
        }

        unset($customerInfo['password']);

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_customer_info', json_encode($customerInfo));
        $this->session->remove('IDXlogin');

        $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $customerInfo['customer_id'], $this->cartList);
        $this->session->set('cart_list', json_encode($this->cartList));

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
        $register = (new CustomerBiz($this->langCode))->register([
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
            unset($register['customer_info']['password']);

            $this->session->renameKey($this->request->domain);
            $this->session->set('sp_customer_info', json_encode($register['customer_info']));
            $this->session->remove('IDXregister');
            $register['url'] = $this->session->get('login_to', '/account.html');

            $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $register['customer_info']['customer_id'], $this->cartList);
            $this->session->set('cart_list', json_encode($this->cartList));

            \Swoole\Event::defer(function () use ($register) {
                $mailData = [
                    'template' => 'welcome',
                    'to_address' => $register['customer_info']['email']
                ];
                (new EmailHelper($this->shopId, $this->host))->sendMail($mailData);
            });
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
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('prod_qty_invalid', $this->langCode)];
        }

        $prodBiz = new ProductBiz();
        $skuInfo = $prodBiz->getSkuQtyPriceListBySkuArr($this->shopId, [$sku]);
        $skuInfo = $skuInfo[$sku][$this->warehouseCode] ?? [];
        if (empty($skuInfo)) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        if ((int)$skuInfo['qty'] <= 0) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('prod_sold_out', $this->langCode)];
        }

        $cartData = [
            'product_id' => (int)$skuInfo['product_id'],
            'sku' => $sku,
            'qty' => $prodQty,
            'price' => (float)$skuInfo['price']
        ];

        $cartQty = $prodQty;
        $isNew = true;
        if (!empty($this->cartList)) {
            foreach ($this->cartList as $cartSku => $cartInfo) {
                $cartQty += $cartInfo['qty'];
                if ($sku == $cartSku) {
                    $this->cartList[$cartSku]['qty'] = $prodQty + $cartInfo['qty'];
                    $this->cartList[$cartSku]['price'] = $cartData['price'];
                    $isNew = false;
                }
            }
        }
        if ($isNew) {
            $this->cartList[$sku] = $cartData;
        }

        $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $this->customerId, $this->cartList);
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
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('prod_qty_invalid', $this->langCode)];
        }

        $skuInfo = (new ProductBiz())->getSkuQtyPriceListBySkuArr($this->shopId, [$sku]);
        $skuInfo = $skuInfo[$sku][$this->warehouseCode] ?? [];
        if (empty($skuInfo)) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        if ((int)$skuInfo['qty'] <= 0) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('prod_sold_out', $this->langCode)];
        }

        $cartData = [
            'product_id' => (int)$skuInfo['product_id'],
            'sku' => $sku,
            'qty' => $prodQty,
            'price' => (float)$skuInfo['price']
        ];

        $prodPrice = (float)format_price($cartData['price'], $this->currency, $prodQty);
        $cartPrice = $prodPrice;
        $cartQty = $prodQty;
        $isNew = true;
        if (!empty($this->cartList)) {
            foreach ($this->cartList as $cartSku => $cartInfo) {
                if ($sku == $cartSku) {
                    $isNew = false;
                    $this->cartList[$cartSku]['qty'] = $prodQty;
                    $this->cartList[$cartSku]['price'] = $cartData['price'];
                } else {
                    $cartPrice += (float)format_price($cartInfo['price'], $this->currency, $cartInfo['qty']);
                    $cartQty += $cartInfo['qty'];
                }
            }
        }
        if ($isNew) {
            $this->cartList[$sku] = $cartData;
        }

        $this->cartList = (new ShoppingBiz())->updateCart($this->shopId, $this->customerId, $this->cartList);
        $this->session->set('cart_list', json_encode($this->cartList));

        $this->session->remove($idempotentField);

        return [
            'status' => 'success',
            'prod_price' => format_price_total($prodPrice, $this->currency),
            'cart_price' => format_price_total($cartPrice, $this->currency)
        ];
    }

    public function deleteCartProduct()
    {
        $sku = $this->post('sku', 'trim,strtoupper');
        if ((new ShoppingBiz())->deleteCartSku($this->shopId, $this->customerId, $sku)) {
            unset($this->cartList[$sku]);
            $this->session->set('cart_list', json_encode($this->cartList));

            return ['status' => 'success'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('remove_failed', $this->langCode)];
    }

    public function deleteAddress()
    {
        $addrId = $this->post('addr', 0);
        if ((new AddressBiz())->deleteAddressById($this->shopId, $this->customerId, (int)$addrId)) {
            return ['status' => 'success'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('remove_failed', $this->langCode)];
    }

    public function setDefaultAddress()
    {
        $addrId = $this->post('addr', 0);
        if ((new CustomerBiz($this->langCode))->setShippingAddress($this->shopId, $this->customerId, (int)$addrId)) {
            return ['status' => 'success'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('remove_failed', $this->langCode)];
    }

    public function getOrderNumbersByDays()
    {
        $days = $this->get('days', 0);
        $days = (int)$days;
        if ($days <= 0) {
            return [
                'status' => 'success',
                'order_numbers' => []
            ];
        }

        if ($days === 1) {
            $start = strtotime('-1 day');
        } else {
            $start = strtotime('-' . $days . ' days');
        }

        $start = strtotime(date('Y-m-d 00:00:00', $start));
        $condition = [
            'shop_id' => $this->shopId,
            'customer_id' => $this->customerId,
            'created_at_between' => [$start, time()]
        ];
        $orderNumbers = (new OrderBiz())->getOrderList($condition, [], 1, 1000);
        $orderNumbers = empty($orderNumbers) ? [] : array_column($orderNumbers, 'order_number');

        return [
            'status' => 'success',
            'order_numbers' => $orderNumbers
        ];
    }

    public function getZoneList()
    {
        $countryId = (int)$this->get('country_id', 0);
        $zoneList = (new AddressBiz())->getZoneList($this->shopId, $countryId, 1, 1000);
        return [
            'status' => 'success',
            'zone_list' => $zoneList ? $zoneList : []
        ];
    }
}
