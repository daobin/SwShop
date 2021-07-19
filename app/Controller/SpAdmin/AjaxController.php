<?php
/**
 * 异步交互控制
 * User: dao bin
 * Date: 2021/7/16
 * Time: 14:05
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\DbHelper;
use App\Helper\LanguageHelper;
use App\Helper\RouteHelper;

class AjaxController extends Controller
{
    public function login()
    {
        $account = $this->request->post['account'] ?? '';
        $password = $this->request->post['password'] ?? '';

        $adminInfo = DbHelper::connection()->table('admin')->where(
            [
                'shop_id' => $this->request->shop_id,
                'account' => $account
            ])->find();
        if (empty($adminInfo) || !password_verify($password, $adminInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_valid_account_password')];
        }

        $this->session->set('spadmin_login_status', 'Y');
        return ['status' => 'success', 'url' => RouteHelper::buildUrl('SpAdmin.Index.index', ['suffix' => ''])];
    }
}
