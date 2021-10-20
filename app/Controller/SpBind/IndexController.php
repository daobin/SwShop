<?php
/**
 * User: dao bin
 * Date: 2021/10/19
 * Time: 18:02
 */
declare(strict_types=1);

namespace App\Controller\SpBind;

use App\Biz\AdminBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        list($cnTime, $usTime, $ukTime) = get_world_times();

        return $this->render([
            'admin_name' => $this->sysOperator,
            'cn_time' => $cnTime,
            'us_time' => $usTime,
            'uk_time' => $ukTime
        ]);
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);
        return $this->render(['csrf_token' => $safeHelper->buildCsrfToken('BD', 'login')]);
    }

    public function loginProcess()
    {
        if (!$this->request->isPost) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $account = $this->post('account');
        $password = $this->post('password');
        if ($account == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_account', $this->langCode)];
        }
        if ($password == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_password', $this->langCode)];
        }

        $adminInfo = (new AdminBiz())->getSysAdminByAccount($account);
        if (empty($adminInfo) || !password_verify($password, $adminInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_valid_account_password', $this->langCode)];
        }

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_bind_info', json_encode($adminInfo));
        $this->session->remove('BDlogin');
        return ['status' => 'success', 'url' => '/spbind'];
    }

    public function logout()
    {
        $this->session->clear();
        $this->response->redirect('/spbind/login.html');
    }
}
