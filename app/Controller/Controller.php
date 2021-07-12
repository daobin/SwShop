<?php
declare(strict_types=1);

namespace App\Controller;

use App\Helper\DbHelper;
use App\Helper\LanguageHelper;
use App\Helper\TemplateHelper;

class Controller
{
    protected $request;
    protected $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->initCheck();
    }

    private function initCheck()
    {
        if (empty($this->request->header['host'])) {
            throw new \Exception('Request Invalid');
        }

        $host = trim($this->request->header['host']);
        $domain = explode('.', $host);
        $domainArrCnt = count($domain);
        if ($domainArrCnt < 2) {
            throw new \Exception('Request Invalid');
        }

        $domain = $domain[$domainArrCnt - 2] . '.' . $domain[$domainArrCnt - 1];
        $this->shopCheck($domain);
    }

    private function shopCheck($domain)
    {
        $shopInfo = DbHelper::connection()->table('sys_shop')
            ->fields(['shop_id', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code'])
            ->whereOr(['shop_domain' => $domain, 'shop_domain2' => $domain])
            ->orderBy(['shop_id' => 'desc'])
            ->find();

        if (empty($shopInfo) || (int)$shopInfo['shop_status'] !== 1) {
            throw new \Exception('Website Invalid');
        }

        $redirectStatus = (int)$shopInfo['shop_domain2_redirect_code'];
        if ($domain == $shopInfo['shop_domain2'] && in_array($redirectStatus, [301, 302], true) && !empty($shopInfo['shop_domain'])) {
            $this->response->redirect('http://' . $shopInfo['shop_domain'], $redirectStatus);
        }
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
        return LanguageHelper::get('invalid_access');
    }
}
