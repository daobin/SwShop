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
use App\Helper\RouteHelper;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        return $this->render();
    }

    public function dashboard()
    {
        return $this->render();
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);
        return $this->render(['csrf_token' => $safeHelper->buildCsrfToken('BG', 'login')]);
    }

    public function logout()
    {
        $this->session->set('spadmin_login_status', 'N');
        $this->response->redirect('/spadmin/login.html');
    }
}
