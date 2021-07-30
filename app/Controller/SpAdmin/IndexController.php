<?php
/**
 * 店铺管理后台首页
 * User: dao bin
 * Date: 2021/7/14
 * Time: 15:59
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\AdminBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\RouteHelper;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        $data = [
            'admin_name' => $this->operator
        ];
        return $this->render($data);
    }

    public function dashboard()
    {
        $data = [
            'admin_name' => $this->operator
        ];
        return $this->render($data);
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);
        return $this->render(['csrf_token' => $safeHelper->buildCsrfToken('BG', 'login')]);
    }

    public function loginProcess()
    {
        if(!$this->request->isPost){
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $account = $this->post('account');
        $password = $this->post('password');
        if ($account == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_account')];
        }
        if ($password == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_password')];
        }

        $adminInfo = (new AdminBiz())->getAdminByAccount($this->shopId, $account);
        if (empty($adminInfo) || !password_verify($password, $adminInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_valid_account_password')];
        }

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_admin_info', json_encode($adminInfo));
        $this->session->remove('BGlogin');
        return ['status' => 'success', 'url' => RouteHelper::buildUrl('SpAdmin.Index.index', ['suffix' => ''])];
    }

    public function logout()
    {
        $this->session->clear();
        $this->response->redirect('/spadmin/login.html');
    }
}
