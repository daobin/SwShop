<?php
/**
 * 店铺管理后台首页
 * User: dao bin
 * Date: 2021/7/14
 * Time: 15:59
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        return 'SP SpAdmin 页';
    }

    public function login(){
        $safeHelper = new SafeHelper($this->request, $this->response);
        return $this->render(['csrf_token' => $safeHelper->buildCsrfToken('BG', 'login')]);
    }
}
