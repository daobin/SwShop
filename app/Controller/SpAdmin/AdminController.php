<?php
/**
 * 管理员
 * User: dao bin
 * Date: 2021/8/31
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\AdminBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class AdminController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $operator = $this->operatorId == 1 ? '' : $this->operator;
            return [
                'code' => 0,
                'data' => (new AdminBiz())->getAdminList($this->shopId, $operator)
            ];
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'admin')
        ]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $adminId = (int)$this->get('admin_id', 0);

        $data = [
            'admin_info' => (new AdminBiz())->getAdminById($this->shopId, $adminId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'admin' . $adminId)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $adminBiz = new AdminBiz();

        $adminId = $this->get('admin_id', 0);
        $account = $this->post('account');
        $password = $this->post('pwd');
        if ($account == '') {
            return ['status' => 'fail', 'msg' => '请输入账号'];
        }

        $accountInfo = $adminBiz->getAdminByAccount($this->shopId, $account);
        if (!empty($accountInfo) && (int)$accountInfo['admin_id'] !== (int)$adminId) {
            return ['status' => 'fail', 'msg' => '账号已存在'];
        }

        $adminInfo = $adminBiz->getAdminById($this->shopId, (int)$adminId);
        if (empty($adminInfo) && $password == '') {
            return ['status' => 'fail', 'msg' => '请输入密码'];
        }

        $data = [
            'admin_id' => $adminId,
            'shop_id' => $this->shopId,
            'account' => $account,
            'operator' => $this->operator
        ];
        if ($password !== '') {
            $data['password'] = $password;
        }

        if ($adminBiz->save($data) > 0) {
            if ($adminId == $this->operatorId) {
                return ['status' => 'success', 'msg' => '保存成功', 'url' => '/spadmin/logout'];
            }

            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $adminId = $this->post('admin_id', 0);
        if ((new AdminBiz())->delById($this->shopId, (int)$adminId) > 0) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
