<?php
/**
 * 用户管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\AddressBiz;
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
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime($startTime)));
                }else{
                    $startTime = 0;
                }
                $endTime = $this->get('end_time', '');
                if ($endTime !== '') {
                    $endTime = strtotime(date('Y-m-d 23:59:59', strtotime($endTime)));
                }else{
                    $endTime = time();
                }
                $condition['register_at_between'] = [$startTime, $endTime];
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

            $customerBiz = new CustomerBiz($this->langCode);
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
        if ($this->request->isPost) {
            return $this->save();
        }

        $customerBiz = new CustomerBiz($this->langCode);

        $customerId = $this->get('customer_id', 0);
        $customerId = (int)$customerId;
        $customerInfo = $customerBiz->getCustomerById($this->shopId, $customerId);
        if (empty($customerInfo)) {
            return LanguageHelper::get('invalid_customer', $this->langCode);
        }

        return $this->render([
            'customer_info' => $customerInfo,
            'address_list' => (new AddressBiz())->getAddressListByCustomerId($this->shopId, $customerId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'customer_' . $customerId)
        ]);
    }

    private function save()
    {
        $customerId = $this->get('customer_id', 0);
        $save = (new CustomerBiz($this->langCode))->updateBaseInfo([
            'shop_id' => $this->shopId,
            'customer_id' => $customerId,
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name'),
            'email' => $this->post('email'),
            'password' => $this->post('pwd'),
            'operator' => $this->operator
        ]);
        if (!isset($save['status']) || $save['status'] != 'success') {
            return $save;
        }

        return ['status' => 'success', 'msg' => '保存成功'];
    }
}
