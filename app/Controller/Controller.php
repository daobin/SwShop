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
    protected $langCodes;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->session = new SessionHelper($this->request, $this->response);

        $this->spAdminInfo = $this->session->get('sp_admin_info', '');
        $this->spAdminInfo = $this->spAdminInfo ? json_decode($this->spAdminInfo, true) : [];
        $this->langCodes = ConfigHelper::getLangCodes();
    }

    public function render($data = [], $template = null)
    {
        $template ??= implode('/', [$this->request->module, $this->request->controller, $this->request->action]);

        // 静态资源时间戳
        $timestamp = (new ConfigBiz())->getConfigByKey($this->request->shop_id, 'TIMESTAMP');
        $data['timestamp'] = $timestamp['config_value'] ?? '?'.date('YmdH');

        return TemplateHelper::view($template, $data);
    }

    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        $this->response->status(404);
        return LanguageHelper::get('invalid_request');
    }
}
