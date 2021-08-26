<?php
/**
 * 仓库管理
 * User: dao bin
 * Date: 2021/8/25
 * Time: 13:40
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\WarehouseBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class WarehouseController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new WarehouseBiz())->getWarehouseList($this->shopId)
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'warehouse')
        ];
        return $this->render($data);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $code = $this->get('code', '', 'trim,strtoupper');

        $warehouseBiz = new WarehouseBiz();
        $warehouseInfo = $warehouseBiz->getWarehouseByCode($this->shopId, $code);

        $data = [
            'warehouse_code' => $code,
            'sort' => $warehouseInfo['sort'] ?? '',
            'warehouses' => $warehouseBiz->getSysWarehouses(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $code)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $origCode = $this->get('code', '', 'trim,strtoupper');
        $code = $this->post('code', '', 'trim,strtoupper');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '请选择仓库'];
        }

        $warehouseBiz = new WarehouseBiz();
        if ($origCode !== $code && $warehouseBiz->getWarehouseByCode($this->shopId, $code)) {
            return ['status' => 'fail', 'msg' => '仓库已存在'];
        }

        $sysWarehouse = $warehouseBiz->getSysWarehouseByCode($code);
        if (empty($sysWarehouse)) {
            return ['status' => 'fail', 'msg' => '仓库无效'];
        }

        $data = [
            'warehouse_code' => $code,
            'warehouse_name' => $sysWarehouse['warehouse_name'],
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($warehouseBiz->saveWarehouse($this->shopId, $origCode, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $code = $this->post('code', '', 'trim,strtoupper');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '仓库无效'];
        }

        if ((new WarehouseBiz())->delWarehouseByCode($this->shopId, $code)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }

}
