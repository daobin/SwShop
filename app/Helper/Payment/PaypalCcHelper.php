<?php
/**
 * Paypal 信用卡支付
 * User: AT0103
 * Date: 2021/9/16
 * Time: 14:14
 */
declare(strict_types=1);

namespace App\Helper\Payment;

use App\Biz\AddressBiz;
use App\Biz\ConfigBiz;
use App\Biz\CurrencyBiz;
use App\Biz\OrderBiz;
use App\Biz\PaymentBiz;
use App\Biz\PaypalBiz;
use App\Biz\ShoppingBiz;
use App\Helper\LanguageHelper;
use App\Helper\SessionHelper;

class PaypalCcHelper
{
    private $paymentCode;
    private $baseUrl;
    private $shopId;
    private $langCode;
    private $currency;
    private $session;
    private $paymentInfo;

    public function __construct($request, $response)
    {
        $this->paymentCode = 'paypal_cc';

        $this->baseUrl = 'http://' . $request->header['host'];
        $this->shopId = $request->shopId;
        $this->langCode = $request->langCode ?? 'en';
        $this->currency = $request->currency ?? [];

        $this->session = new SessionHelper($request, $response);
        $cfgList = (new ConfigBiz())->getConfigListByGroup($this->shopId, $this->paymentCode);

        $this->paymentInfo = (new PaymentBiz())->getPaymentByCode($this->shopId, $this->paymentCode);
        $this->paymentInfo['cfg_list'] = !empty($cfgList) ? array_column($cfgList, 'config_value', 'config_key') : [];
    }

    public function payCancel(string $token): array
    {
        $orderBiz = new OrderBiz();
        $orderInfo = $orderBiz->getOrderByPpToken($this->shopId, $token);
        if (empty($orderInfo)) {
            return ['msg' => LanguageHelper::get('invalid_order', $this->langCode)];
        }

        $canceledId = get_order_status_id('canceled');
        if ($orderInfo['order_status_id'] == $canceledId) {
            return [];
        }

        $successStatusIds = [
            get_order_status_id('pending'),
            get_order_status_id('in_process'),
            get_order_status_id('shipped'),
        ];
        if (in_array($orderInfo['order_status_id'], $successStatusIds)) {
            $this->session->set('success_order_number', $orderInfo['order_number']);
            return ['url' => '/shopping/success.html'];
        }

        $comment = get_order_status_note($canceledId, $this->langCode);
        $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $canceledId, $comment);

        $comment = LanguageHelper::get('pp_page_canceled_note', $this->langCode);
        $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $canceledId, $comment, false);

        return ['msg' => LanguageHelper::get('pp_page_canceled_note', $this->langCode)];
    }

    public function payProcess(string $token)
    {
        $orderBiz = new OrderBiz();
        $orderInfo = $orderBiz->getOrderByPpToken($this->shopId, $token);
        if (empty($orderInfo)) {
            return ['msg' => LanguageHelper::get('invalid_order', $this->langCode)];
        }

        $payRes = $this->doRequest('authorize_order', $token);
//        print_r('PP CC >>' . PHP_EOL);
//        print_r($payRes);

        $response = $payRes['data'] ?? [];
        $paymentId = get_paypal_response_val($response, 'payment_id');
        $paymentCreateTime = get_paypal_response_val($response, 'payment_create_time');
        $paymentAmountInfo = get_paypal_response_val($response, 'payment_amount');
        $payer = get_paypal_response_val($response, 'payer');

        (new PaypalBiz())->add([
            'shop_id' => $this->shopId,
            'order_id' => $orderInfo['order_id'],
            'operation' => 'AuthorizeOrder',
            'ack' => $payRes['status'] ?? 'fail',
            'payment_code' => $this->paymentCode,
            'payment_status' => 'Authorization',
            'payment_date' => $paymentCreateTime,
            'txn_id' => $paymentId,
            'currency_code' => $paymentAmountInfo['currency_code'] ?? '',
            'amount' => $paymentAmountInfo['value'] ?? 0,
            'payer_email' => $payer['email_address'] ?? '',
            'payer_id' => $payer['payer_id'] ?? ''
        ]);

        $success = false;
        if (isset($payRes['status']) && $payRes['status'] == 'success') {
            $success = true;
        } else if (isset($payRes['code']) && $payRes['code'] == 'ORDER_ALREADY_AUTHORIZED') {
            $success = true;
        }

        if ($success) {
            $pendingId = get_order_status_id('pending');
            $comment = get_order_status_note($pendingId, $this->langCode);
            $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $pendingId, $comment);

            $comment = 'Txn ID: ' . $paymentId;
            $comment .= '<br/>Timestamp: ' . $paymentCreateTime;
            $comment .= '<br/>Payment Status: Authorization Created';
            $comment .= '<br/>Currency: ' . ($paymentAmountInfo['currency_code'] ?? '');
            $comment .= '<br/>Amount: ' . ($paymentAmountInfo['value'] ?? '0.00');
            $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $pendingId, $comment, false);

            (new ShoppingBiz())->deleteCustomerCart($this->shopId, (int)$orderInfo['customer_id']);
            $this->session->set('cart_list', '[]');
            $this->session->set('order_summary', '{}');

            $this->session->set('success_order_number', $orderInfo['order_number']);
            return [
                'url' => '/shopping/success.html',
                'customer_name' => $orderInfo['customer_name'],
                'email' => $orderInfo['customer_email'],
                'order_number' => $orderInfo['order_number'],
                'order_total' => $orderInfo['order_total'],
                'order_date' => date('Y-m-d', $orderInfo['created_at'])
            ];
        }

        $canceledId = get_order_status_id('canceled');
        $comment = get_order_status_note($canceledId, $this->langCode);
        $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $canceledId, $comment);

        $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], $canceledId, $payRes['data'], false);

        return ['msg' => $payRes['data'] ?? LanguageHelper::get('payment_rejected_tip', $this->langCode)];
    }

    public function orderProcess(array $customerInfo, array $addressInfo, array $paymentInfo): array
    {
        if ($this->shopId <= 0 || empty($customerInfo['customer_id']) || empty($addressInfo['street_address']) || empty($paymentInfo['method_code'])) {
            return ['msg' => LanguageHelper::get('payment_rejected_tip', $this->langCode)];
        }

        $orderSummary = $this->session->get('order_summary', '{}');
        $orderSummary = json_decode($orderSummary, true);

        $purchaseUnit = [
            'amount' => [
                'currency_code' => $this->currency['currency_code'],
                'value' => format_price($orderSummary['totals']['total']['price'], $this->currency),
                'breakdown' => [
                    'item_total' => [
                        'currency_code' => $this->currency['currency_code'],
                        'value' => format_price($orderSummary['totals']['subtotal']['price'], $this->currency)
                    ],
                    'shipping' => [
                        'currency_code' => $this->currency['currency_code'],
                        'value' => format_price($orderSummary['totals']['shipping']['price'], $this->currency)
                    ]
                ]
            ]
        ];

        if (isset($orderSummary['totals']['insurance'])) {
            $purchaseUnit['amount']['breakdown']['insurance'] = [
                'currency_code' => $this->currency['currency_code'],
                'value' => format_price($orderSummary['totals']['insurance']['price'], $this->currency)
            ];
        }

        if (isset($orderSummary['totals']['coupon'])) {
            $purchaseUnit['amount']['breakdown']['discount'] = [
                'currency_code' => $this->currency['currency_code'],
                'value' => format_price($orderSummary['totals']['coupon']['price'], $this->currency)
            ];
        }

        foreach ($orderSummary['prod_list'] as $sku => $prodInfo) {
            $item = [
                'name' => $sku,
                'unit_amount' => [
                    'currency_code' => $this->currency['currency_code'],
                    'value' => format_price((float)$prodInfo['price'], $this->currency)
                ],
                'quantity' => $prodInfo['qty'],
            ];

            $purchaseUnit['items'][] = $item;
        }
        reset($orderSummary['prod_list']);

//        $countryInfo = (new AddressBiz())->getCountryById($this->shopId, (int)$addressInfo['country_id']);
//        $purchaseUnit['shipping'] = [
//            'name' => [
//                'full_name' => $addressInfo['first_name'] . ' ' . $addressInfo['last_name']
//            ],
//            'type' => 'SHIPPING',
//            'address' => [
//                'address_line_1' => $addressInfo['street_address'],
//                'address_line_2' => $addressInfo['street_address_sub'],
//                'admin_area_2' => $addressInfo['city'],
//                'admin_area_1' => $addressInfo['zone_name'],
//                'postal_code' => $addressInfo['postcode'],
//                'country_code' => $countryInfo['iso_code_2'] ?? '',
//            ]
//        ];

        $request = [
            'intent' => 'AUTHORIZE',
            'purchase_units' => [$purchaseUnit],
            'application_context' => [
                'return_url' => $this->baseUrl . '/shopping/payment-handler.html?pm=' . $this->paymentCode,
                'cancel_url' => $this->baseUrl . '/shopping/payment-cancel.html?pm=' . $this->paymentCode
            ]
        ];

        $payRes = $this->doRequest('create_order', $request);
        if (!isset($payRes['status']) || $payRes['status'] != 'success') {
            return ['msg' => $payRes['data'] ?? ''];
        }

        // 订单口令
        $token = trim($payRes['data']['id']);

        $orderSummary['pp_token'] = $token;
        $orderSummary['customer_info'] = $customerInfo;
        $orderSummary['address_info'] = $addressInfo;
        $orderSummary['payment_info'] = $paymentInfo;
        $orderSummary['shipping_info'] = [
            'method_name' => 'Free Shipping',
            'method_code' => 'free'
        ];
        $orderSummary['currency_info'] = $this->currency;
        $orderSummary['default_currency_info'] = (new CurrencyBiz())->getDefaultCurrency($this->shopId);

        $orderSummary['order_status_id'] = get_order_status_id('waiting');
        $orderSummary['status_comment'] = get_order_status_note($orderSummary['order_status_id'], $this->langCode);

        $add = (new OrderBiz())->createOrder($this->shopId, $orderSummary);
        if ($add) {
            return ['url' => $this->paymentInfo['cfg_list']['PAYPAL_CC_CHECKOUT_URL'] . '?token=' . $token];
        }

        return ['msg' => LanguageHelper::get('order_generation_failed', $this->langCode)];
    }

    public function approve(int $shopId, int $orderId): array
    {
        $paypal = (new PaypalBiz())->getAuthorizationByOrderId($shopId, $orderId);
        if (empty($paypal['txn_id'])) {
            return ['status' => 'fail', 'msg' => '找不到订单支付信息'];
        }

        $payRes = $this->doRequest('authorize_capture', $paypal['txn_id']);
        if (!isset($payRes['status']) || $payRes['status'] != 'success') {
            return ['status' => 'fail', 'msg' => $payRes['data'] ?? ''];
        }

        $orderBiz = new OrderBiz();

        $inProcessId = get_order_status_id('in_process');
        $comment = get_order_status_note($inProcessId, $this->langCode);
        $orderBiz->updateOrderStatusById($this->shopId, $orderId, $inProcessId, $comment);

        $response = $payRes['data'] ?? [];
        $comment = '系统预授权确认成功';
        $comment .= '<br/>预授权交易号: ' . $paypal['txn_id'];
        $comment .= '<br/>收款交易号: ' . get_paypal_response_val($response, 'id');
        $comment .= '<br/>收款状态: ' . get_paypal_response_val($response, 'status');
        $orderBiz->updateOrderStatusById($this->shopId, $orderId, $inProcessId, $comment, false);

        return ['status' => 'success'];
    }

    public function reject(int $shopId, int $orderId): array
    {
        $paypal = (new PaypalBiz())->getAuthorizationByOrderId($shopId, $orderId);
        if (empty($paypal['txn_id'])) {
            return ['status' => 'fail', 'msg' => '找不到订单支付信息'];
        }

        $payRes = $this->doRequest('authorize_void', $paypal['txn_id']);
        if (!isset($payRes['status']) || $payRes['status'] != 'success') {
            return ['status' => 'fail', 'msg' => $payRes['data'] ?? ''];
        }

        $orderBiz = new OrderBiz();

        $canceledId = get_order_status_id('canceled');
        $comment = get_order_status_note($canceledId, $this->langCode);
        $orderBiz->updateOrderStatusById($this->shopId, $orderId, $canceledId, $comment);

        $comment = '系统预授权撤销成功';
        $comment .= '<br/>预授权交易号: ' . $paypal['txn_id'];
        $orderBiz->updateOrderStatusById($this->shopId, $orderId, $canceledId, $comment, false);

        return ['status' => 'success'];
    }

    private function doRequest($service, $request): array
    {
        $basicAuth = $this->paymentInfo['cfg_list']['PAYPAL_CC_API_CLIENT_ID'] . ':' . $this->paymentInfo['cfg_list']['PAYPAL_CC_API_SECRET'];

        $chOptions = [
            CURLOPT_URL => $this->paymentInfo['cfg_list']['PAYPAL_CC_API_URL'],
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($basicAuth)
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ];

        switch ($service) {
            // 创建支付订单
            case 'create_order':
                $chOptions[CURLOPT_URL] .= '/v2/checkout/orders';
                $chOptions[CURLOPT_POST] = true;
                $request = is_array($request) ? $request : [];
                $chOptions[CURLOPT_POSTFIELDS] = json_encode($request);

                $request = '';
                break;
            // 获取支付订单
            case 'get_order':
                $chOptions[CURLOPT_URL] .= '/v2/checkout/orders/%s';
                break;
            // 预授权支付订单
            case 'authorize_order':
                $chOptions[CURLOPT_URL] .= '/v2/checkout/orders/%s/authorize';
                $chOptions[CURLOPT_POST] = true;
                break;
            // 预授权确认收款
            case 'authorize_capture':
                $chOptions[CURLOPT_URL] .= '/v2/payments/authorizations/%s/capture';
                $chOptions[CURLOPT_POST] = true;
                $chOptions[CURLOPT_POSTFIELDS] = json_encode(['final_capture' => true]);
                break;
            // 预授权取消收款
            case 'authorize_void':
                $chOptions[CURLOPT_URL] .= '/v2/payments/authorizations/%s/void';
                $chOptions[CURLOPT_POST] = true;
                break;
            default:
                return ['status' => 'fail', 'data' => 'Service Error'];
                break;
        }
        $chOptions[CURLOPT_URL] = sprintf($chOptions[CURLOPT_URL], trim($request));

        $ch = curl_init();
        curl_setopt_array($ch, $chOptions);
        $res = curl_exec($ch);
        $errNo = curl_errno($ch);
        if ($errNo > 0) {
            $res = [
                'status' => 'fail',
                'data' => $errNo . ' : ' . curl_error($ch)
            ];
            return $res;
        }
        curl_close($ch);

        $res = json_decode($res, true);
        if (isset($res['message'])) {
            $resTmp = [
                'status' => 'fail',
                'data' => $res['message']
            ];
            if (isset($res['details'])) {
                $resTmp['code'] = $res['details'][0]['issue'];
            }

            return $resTmp;
        }
        if (isset($res['error'])) {
            return [
                'status' => 'fail',
                'data' => implode(' :: ', $res)
            ];
        }

        $res = ['status' => 'success', 'data' => $res];
        return $res;
    }
}
