<?php
/**
 * Paypal 支付
 * User: AT0103
 * Date: 2021/9/16
 * Time: 14:14
 */
declare(strict_types=1);

namespace App\Helper\Payment;

use App\Biz\ConfigBiz;
use App\Biz\PaymentBiz;
use App\Helper\SessionHelper;

class PaypalHelper
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
        $this->paymentCode = 'paypal';

        $this->baseUrl = 'https://' . $request->header['host'];
        $this->shopId = $request->shopId;
        $this->langCode = $request->langCode ?? 'en';
        $this->currency = $request->currency ?? [];

        $this->session = new SessionHelper($request, $response);
        $cfgList = (new ConfigBiz())->getConfigListByGroup($this->shopId, $this->paymentCode);

        $this->paymentInfo = (new PaymentBiz())->getPaymentByCode($this->shopId, $this->paymentCode);
        $this->paymentInfo['cfg_list'] = !empty($cfgList) ? array_column($cfgList, 'config_value', 'config_key') : [];
    }

    public function orderProcess(array $addressInfo, array $paymentInfo): array
    {
        return ['msg'];
    }
}
