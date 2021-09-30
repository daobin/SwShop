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
            $condition = ['shop_id' => $this->shopId];
            $searchKey = $this->get('search_key', '');
            if (!empty($searchKey)) {
                $searchValue = $this->get('search_value', '');
                if ($searchValue !== '') {
                    switch ($searchKey) {
                        case 'username':
                            $condition['customer_name_like'] = $searchValue;
                            break;
                        case 'email':
                            $condition['email'] = $searchValue;
                            break;
                    }
                }
            } else {
                $startTime = $this->get('start_time', '');
                if ($startTime !== '') {
                    $condition['start_register_at'] = strtotime(date('Y-m-d 00：00：00', strtotime($startTime)));
                }
                $endTime = $this->get('end_time', '');
                if ($endTime !== '') {
                    $condition['end_register_at'] = strtotime(date('Y-m-d 23:59:59', strtotime($endTime)));
                }
                $customerFrom = $this->get('customer_from', '');
                if ($customerFrom !== '') {
                    $condition['device_from'] = $customerFrom;
                }
                $customerType = $this->get('customer_type', '');
                if ($customerType !== '') {
                    $condition['customer_type'] = $customerType;
                }
            }

            $page = $this->request->get['page'] ?? 1;
            $pageSize = $this->request->get['limit'] ?? 10;

            $customerBiz = new CustomerBiz();
            $customerList = $customerBiz->getCustomerList($condition, [], (int)$page, (int)$pageSize);

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
        if($this->request->isPost){
            return $this->save();
        }

        $customerBiz = new CustomerBiz();

        $customerId = $this->get('customer_id', 0);
        $customerInfo = $customerBiz->getCustomerById($this->shopId, (int)$customerId);
        if (empty($customerInfo)) {
            return $this->response->redirect('/spadmin/customer.html');
        }

        return $this->render([
            'customer_info' => $customerInfo,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'customer_' . $customerId)
        ]);
    }

    private function save(){
        $customerBiz = new CustomerBiz();

        $customerId = $this->get('customer_id', 0);
        $customerInfo = $customerBiz->getCustomerById($this->shopId, (int)$customerId);

        $firstName = $this->post('first_name');
        $lastName = $this->post('last_name');
        $email = $this->post('email');
        $password = $this->post('pwd');

        return ['status' => 'success', 'msg' => '保存成功'];
    }
}
