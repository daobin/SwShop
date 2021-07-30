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
    protected $spAdminInfo;
    protected $operator;
    protected $langCodes;
    protected $shopId;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->session = new SessionHelper($this->request, $this->response);

        $this->spAdminInfo = $this->session->get('sp_admin_info', '');
        $this->spAdminInfo = $this->spAdminInfo ? json_decode($this->spAdminInfo, true) : [];
        $this->operator = $this->spAdminInfo['account'] ?? '--';
        $this->langCodes = ConfigHelper::getLangCodes();
        $this->shopId = $this->request->shop_id;
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
        $template ??= implode('/', [$this->request->module, $this->request->controller, $this->request->action]);

        // 静态资源时间戳
        $timestamp = (new ConfigBiz())->getConfigByKey($this->shopId, 'TIMESTAMP');
        $data['timestamp'] = $timestamp['config_value'] ?? '?' . date('YmdH');

        return TemplateHelper::view($template, $data);
    }

    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        $this->response->status(404);
        return LanguageHelper::get('invalid_request');
    }
}
