<?php
/**
 * Paypal 信用卡支付
 * User: AT0103
 * Date: 2021/9/16
 * Time: 14:14
 */
declare(strict_types=1);

namespace App\Helper\Payment;

use App\Biz\ConfigBiz;
use App\Biz\PaymentBiz;

class PaypalCcHelper
{
    private $paymentInfo;

    public function __construct(int $shopId)
    {
        $cfgList = (new ConfigBiz())->getConfigListByGroup($shopId, 'paypal_cc');
        $this->paymentInfo = (new PaymentBiz())->getPaymentByCode($shopId, 'paypal_cc');
        $this->paymentInfo['cfg_list'] = !empty($cfgList) ? array_column($cfgList, 'config_value', 'config_key') : [];
    }

    private function doRequest($service, $request): array
    {
        $basicAuth = $this->paymentInfo['cfg_list']['API_CLIENT_ID'] . ':'$this->paymentInfo['cfg_list']['API_SECRET'];

        $chOptions = [
            CURLOPT_URL => $this->paymentInfo['cfg_list']['API_URL'],
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

        $res = ['status' => 'success', 'data' => $res];
        return $res;
    }
}
