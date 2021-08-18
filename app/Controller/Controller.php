<?php
declare(strict_types=1);

namespace App\Controller;

use App\Biz\ConfigBiz;
use App\Helper\ConfigHelper;
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
    protected $langCodes;
    protected $shopId;
    protected $host;
    protected $device;
    protected $ip;
    protected $ipCountryIsoCode2;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->session = new SessionHelper($this->request, $this->response);

        $spAdminInfo = $this->session->get('sp_admin_info', '');
        $spAdminInfo = $spAdminInfo ? json_decode($spAdminInfo, true) : [];
        $this->operator = $spAdminInfo['account'] ?? '--';

        $customerInfo = $this->session->get('sp_customer_info');
        $customerInfo = $customerInfo ? json_decode($customerInfo, true) : [];
        $this->customerId = $customerInfo['customer_id'] ?? 0;

        $this->langCodes = ConfigHelper::getLangCodes();
        $this->shopId = $this->request->shop_id;
        $this->host = $this->request->header['host'];

        $this->chkDevice();
        $this->chkClientIp();
    }

    private function chkDevice()
    {
        $this->device = 'PC';

        $ua = $this->request->header['user-agent'] ?? '';
        $ua = strtolower($ua);

        $iphone = strstr($ua, 'mobile') !== false;
        $android = strstr($ua, 'android') !== false;
        $winPhone = strstr($ua, 'phone') !== false;
        $iPad = strstr($ua, 'ipad') !== false;
        $androidPad = $android && strstr($ua, 'mobile') !== false;

        if ($iphone || $android || $winPhone || $iPad || $androidPad) {
            $this->device = 'M';
        }
    }

    private function chkClientIp()
    {
        $this->ip = $this->request->ipLong ?? 0;
        $this->ipCountryIsoCode2 = '';
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
            foreach ($filterArr as $filter) {
                $filter = trim($filter);
                if (empty($filter)) {
                    continue;
                }

                $value = $filter($value);
            }
            $value = strip_tags($value);

            return $value;
        }

        return $value;
    }

    public function render($data = [], $template = null)
    {
        if ($this->request->module === 'Index') {
            $tplTheme = 'Default';
            $template ??= implode('/', [$this->request->module, $tplTheme, $this->request->controller, $this->request->action]);
        } else {
            $template ??= implode('/', [$this->request->module, $this->request->controller, $this->request->action]);
        }

        // Static Resource Timestamp
        $timestamp = (new ConfigBiz())->getConfigByKey($this->shopId, 'TIMESTAMP');
        $data['timestamp'] = $timestamp['config_value'] ?? '?' . date('YmdH');

        // Valid Customer Id
        $data['customer_id'] = $this->customerId;

        // Widget Params
        $data['widget_params'] = [
            'timestamp' => $data['timestamp'],
            'customer_id' => $data['customer_id']
        ];

        return TemplateHelper::view($template, $data);
    }

    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        $this->response->status(404);
        return LanguageHelper::get('invalid_request');
    }
}
