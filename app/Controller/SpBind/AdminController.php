<?php
/**
 * 管理员
 * User: dao bin
 * Date: 2021/8/31
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Controller\SpBind;

use App\Biz\AdminBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class AdminController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $operator = $this->sysOperatorId == 1 ? '' : $this->sysOperator;
            return [
                'code' => 0,
                'data' => (new AdminBiz())->getSysAdminList($operator)
            ];
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'admin')
        ]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $adminId = (int)$this->get('admin_id', 0);

        $data = [
            'admin_info' => (new AdminBiz())->getSysAdminById($adminId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'admin' . $adminId)
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

        $accountInfo = $adminBiz->getSysAdminByAccount($account);
        if (!empty($accountInfo) && (int)$accountInfo['admin_id'] !== (int)$adminId) {
            return ['status' => 'fail', 'msg' => '账号已存在'];
        }

        $adminInfo = $adminBiz->getSysAdminById((int)$adminId);
        if (empty($adminInfo) && $password == '') {
            return ['status' => 'fail', 'msg' => '请输入密码'];
        }

        $data = [
            'admin_id' => $adminId,
            'account' => $account,
            'operator' => $this->sysOperator
        ];
        if ($password !== '') {
            $data['password'] = $password;
        }

        if ($adminBiz->saveSysAdmin($data) > 0) {
            if ($adminId == $this->sysOperatorId) {
                return ['status' => 'success', 'msg' => '保存成功', 'url' => '/spbind/logout'];
            }

            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $adminId = $this->post('admin_id', 0);
        if ((new AdminBiz())->delSysAdminById((int)$adminId) > 0) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
