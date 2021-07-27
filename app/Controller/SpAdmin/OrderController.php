<?php
/**
 * 订单管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class OrderController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $orderList = [];

            return [
                'code' => 0,
                'count' => count($orderList),
                'data' => $orderList
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        $orderNumber = $this->request->get['order_number'] ?? '';
        if (empty($orderNumber)) {
            return LanguageHelper::get('invalid_request');
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'order_' . $orderNumber)
        ]);
    }
}
