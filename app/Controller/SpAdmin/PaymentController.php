<?php
/**
 * 支付方式管理
 * User: dao bin
 * Date: 2021/9/15
 * Time: 17:11
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\ConfigBiz;
use App\Biz\PaymentBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class PaymentController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new PaymentBiz())->getPaymentList($this->shopId)
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'payment')
        ];
        return $this->render($data);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $code = $this->get('code', '', 'trim,strtolower');

        $paymentBiz = new PaymentBiz();
        $paymentInfo = $paymentBiz->getPaymentByCode($this->shopId, $code);

        $cfgList = [];
        if(!empty($paymentInfo)){
            $cfgList = (new ConfigBiz())->getConfigListByGroup($this->shopId, $code);
        }

        $data = [
            'payment_code' => $code,
            'sort' => $paymentInfo['sort'] ?? '',
            'payments' => $paymentBiz->getSysPayments(),
            'cfg_list' => $cfgList,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $code)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $origCode = $this->get('code', '', 'trim,strtolower');
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '请选择支付方式'];
        }

        $paymentBiz = new PaymentBiz();
        if ($origCode !== $code && $paymentBiz->getPaymentByCode($this->shopId, $code)) {
            return ['status' => 'fail', 'msg' => '支付方式已存在'];
        }

        $sysPayment = $paymentBiz->getSysPaymentByCode($code);
        if (empty($sysPayment)) {
            return ['status' => 'fail', 'msg' => '支付方式无效'];
        }

        $data = [
            'method_code' => $code,
            'method_name' => $sysPayment['method_name'],
            'sort' => $this->post('sort', 0),
            'cfg_list' => $this->post('cfg_list', []),
            'operator' => $this->operator
        ];

        if ($paymentBiz->savePayment($this->shopId, $origCode, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '支付方式无效'];
        }

        if ((new PaymentBiz())->delPaymentByCode($this->shopId, $code)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
