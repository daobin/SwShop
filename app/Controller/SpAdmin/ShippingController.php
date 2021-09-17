<?php
/**
 * 货运方式管理
 * User: dao bin
 * Date: 2021/9/15
 * Time: 17:11
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\ShippingBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class ShippingController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new ShippingBiz())->getShippingList($this->shopId)
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'shipping')
        ];
        return $this->render($data);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $code = $this->get('code', '', 'trim,strtolower');

        $shippingBiz = new ShippingBiz();
        $shippingInfo = $shippingBiz->getShippingByCode($this->shopId, $code);

        $data = [
            'shipping_code' => $code,
            'note' => $shippingInfo['note'] ?? '',
            'sort' => $shippingInfo['sort'] ?? '',
            'shippings' => $shippingBiz->getSysShippings(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $code)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $origCode = $this->get('code', '', 'trim,strtolower');
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '请选择货运方式'];
        }

        $shippingBiz = new ShippingBiz();
        if ($origCode !== $code && $shippingBiz->getShippingByCode($this->shopId, $code)) {
            return ['status' => 'fail', 'msg' => '货运方式已存在'];
        }

        $sysShipping = $shippingBiz->getSysShippingByCode($code);
        if (empty($sysShipping)) {
            return ['status' => 'fail', 'msg' => '货运方式无效'];
        }

        $data = [
            'method_code' => $code,
            'method_name' => $sysShipping['method_name'],
            'note' => $this->post('note'),
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($shippingBiz->saveShipping($this->shopId, $origCode, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '货运方式无效'];
        }

        if ((new ShippingBiz())->delShippingByCode($this->shopId, $code)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
