<?php
declare(strict_types=1);

namespace App\Controller;

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

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->session = new SessionHelper($this->request, $this->response);

        $this->spAdminInfo = $this->session->get('sp_admin_info', '');
        $this->spAdminInfo = $this->spAdminInfo ? json_decode($this->spAdminInfo, true) : [];
    }

    public function render($data = [], $template = null)
    {
        $template ??= implode('/', [$this->request->module, $this->request->controller, $this->request->action]);
        return TemplateHelper::view($template, $data);
    }

    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        $this->response->status(404);
        return LanguageHelper::get('invalid_request');
    }
}
