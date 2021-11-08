<?php
/**
 * 币种管理
 * User: dao bin
 * Date: 2021/8/25
 * Time: 13:40
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\CurrencyBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class CurrencyController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new CurrencyBiz())->getCurrencyList($this->shopId)
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'currency')
        ];
        return $this->render($data);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $code = $this->get('code', '', 'trim,strtoupper');
        print_r($code);

        $currencyBiz = new CurrencyBiz();
        $currencyInfo = $currencyBiz->getCurrencyByCode($this->shopId, $code);

        $data = [
            'currency_code' => $code,
            'currency_info' => $currencyInfo,
            'currency_list' => $currencyBiz->getSysCurrencyList(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $code)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $origCode = $this->get('code', '', 'trim,strtoupper');
        $code = $this->post('code', '', 'trim,strtoupper');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '请选择币种'];
        }

        $currencyBiz = new CurrencyBiz();
        if ($origCode !== $code && $currencyBiz->getCurrencyByCode($this->shopId, $code)) {
            return ['status' => 'fail', 'msg' => '币种已存在'];
        }

        $sysCurrency = $currencyBiz->getSysCurrencyByCode($code);
        if (empty($sysCurrency)) {
            return ['status' => 'fail', 'msg' => '币种无效'];
        }

        $data = [
            'currency_code' => $code,
            'currency_name' => $sysCurrency['currency_name'],
            'symbol_left' => $sysCurrency['symbol_left'],
            'symbol_right' => $sysCurrency['symbol_right'],
            'decimal_point' => $this->post('decimal_point'),
            'thousands_point' => $this->post('thousands_point'),
            'value' => $this->post('value'),
            'decimal_places' => $this->post('decimal_places', 0),
            'icon_path' => '',
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($currencyBiz->saveWarehouse($this->shopId, $origCode, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $code = $this->post('code', '', 'trim,strtoupper');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '币种无效'];
        }

        if ((new CurrencyBiz())->delCurrencyByCode($this->shopId, $code)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
