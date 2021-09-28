<?php
declare(strict_types=1);

namespace App\Controller;

use App\Biz\ConfigBiz;
use App\Biz\WarehouseBiz;
use App\Helper\LanguageHelper;
use App\Helper\SessionHelper;
use App\Helper\TemplateHelper;

class Controller
{
    protected $request;
    protected $response;
    /**
     * @var SessionHelper
     */
    protected $session;
    protected $operator;
    protected $customerId;
    protected $shippingAddressId;
    protected $langCode;
    protected $shopId;
    protected $host;
    protected $device;
    protected $deviceFrom;
    protected $ip;
    protected $ipCountryIsoCode2;
    protected $currency;
    protected $cartList;
    protected $cartQty;
    protected $warehouseCode;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->initParams();
    }

    private function initParams()
    {
        $this->session = new SessionHelper($this->request, $this->response);

        $spAdminInfo = $this->session->get('sp_admin_info', '');
        $spAdminInfo = $spAdminInfo ? json_decode($spAdminInfo, true) : [];
        $this->operator = $spAdminInfo['account'] ?? '';

        $customerInfo = $this->session->get('sp_customer_info');
        $customerInfo = $customerInfo ? json_decode($customerInfo, true) : [];
        $this->customerId = $customerInfo['customer_id'] ?? 0;
        $this->shippingAddressId = $customerInfo['shipping_address_id'] ?? 0;

        $this->host = $this->request->header['host'];
        $this->shopId = $this->request->shopId;
        $this->langCode = $this->request->langCode ?? 'en';
        $this->currency = $this->request->currency ?? [];

        $warehouse = (new WarehouseBiz())->getDefaultWarehouse($this->shopId);
        $this->warehouseCode = strtoupper($warehouse['warehouse_code'] ?? '-');

        $this->chkDevice();
        $this->chkClientIp();
        $this->getCartList();
    }

    private function chkDevice()
    {
        $this->device = 'PC';
        $this->deviceFrom = 'PC';

        $ua = $this->request->header['user-agent'] ?? '';
        $ua = strtolower($ua);

        $iphone = strstr($ua, 'mobile') !== false;
        $android = strstr($ua, 'android') !== false;
        $winPhone = strstr($ua, 'phone') !== false;
        $iPad = strstr($ua, 'ipad') !== false;
        $androidPad = $android && strstr($ua, 'mobile') !== false;

        if ($iphone || $android || $winPhone || $iPad || $androidPad) {
            $this->device = 'M';
            $this->deviceFrom = 'M';

            if ($iPad || $androidPad) {
                $this->device = 'PAD';
            }
        }
    }

    private function chkClientIp()
    {
        $this->ip = $this->request->ipLong ?? 0;
        $this->ipCountryIsoCode2 = '';
    }

    private function getCartList()
    {
        $this->cartList = $this->session->get('cart_list', '[]');
        $this->cartList = json_decode($this->cartList, true);

        $this->cartQty = 0;
        if (!empty($this->cartList)) {
            foreach ($this->cartList as $cartInfo) {
                $this->cartQty += $cartInfo['qty'];
            }
        }
    }

    public function get($name, $default = '', $filter = 'trim')
    {
        return $this->input('get', $name, $default, $filter);
    }

    public function post($name, $default = '', $filter = 'trim')
    {
        return $this->input('post', $name, $default, $filter);
    }

    private function input($method, $name, $default = '', $filter = 'trim')
    {
        if (!isset($this->request->$method[$name])) {
            return $default;
        }

        $value = $this->request->$method[$name];
        if (empty(trim($filter))) {
            return $value;
        }

        $filterArr = explode(',', $filter);
        if (is_string($value)) {
            return $this->filterInputValue($value, $filterArr);
        } else if (is_array($value)) {
            foreach ($value as $idx => $val) {
                if (is_string($value)) {
                    $value[$idx] = $this->filterInputValue($val, $filterArr);
                }
            }
        }

        return $value;
    }

    private function filterInputValue(string $value, array $filterArr): string
    {
        $value = strip_tags($value);

        if (empty($filter)) {
            return $value;
        }

        foreach ($filterArr as $filter) {
            $filter = trim($filter);
            if (empty($filter)) {
                continue;
            }

            $value = $filter($value);
        }

        return $value;
    }

    public function render($data = [], $template = null)
    {
        if ($this->request->module === 'Index') {
            $tplTheme = 'Default';
            $template = $template ?? implode('/', [$this->request->module, $tplTheme, $this->request->controller, $this->request->action]);
        } else {
            $template = $template ?? implode('/', [$this->request->module, $this->request->controller, $this->request->action]);
        }

        $timestamp = (new ConfigBiz())->getConfigByKey($this->shopId, 'TIMESTAMP');
        $data['timestamp'] = $timestamp['config_value'] ?? '?' . date('YmdH');
        $data['customer_id'] = $this->customerId;
        $data['lang_code'] = $this->langCode;
        $data['currency'] = $this->currency;

        // Widget Params
        $data['widget_params'] = [
            'shop_id' => $this->shopId,
            'timestamp' => $data['timestamp'],
            'customer_id' => $data['customer_id'],
            'cart_qty' => $this->cartQty,
            'controller' => $this->request->controller
        ];

        // Device
        $data['device'] = $this->device;
        $data['device_from'] = $this->deviceFrom;

        return TemplateHelper::view($template, $data);
    }

    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        $this->response->status(404);
        return LanguageHelper::get('invalid_request');
    }
}
