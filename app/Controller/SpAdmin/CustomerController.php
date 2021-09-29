<?php
/**
 * 用户管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\CustomerBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class CustomerController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $customerBiz = new CustomerBiz();
            $customerList = [];

            return [
                'code' => 0,
                'count' => $customerBiz->count,
                'data' => $customerList
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        $customerId = $this->request->get['customer_id'] ?? 0;
        $customerId = (int)$customerId;
        if ($customerId < 0) {
            return LanguageHelper::get('invalid_request');
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'customer_' . $customerId)
        ]);
    }
}
