<?php
/**
 * 订单管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\OrderBiz;
use App\Biz\PaymentBiz;
use App\Biz\PaypalBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class OrderController extends Controller
{
    public function index()
    {
        $orderBiz = new OrderBiz();

        $orderStatuses = $orderBiz->getSysOrderStatuses('zh');

        if ($this->request->isAjax) {
            $condition = ['shop_id' => $this->shopId];
            $searchKey = $this->get('search_key', '');
            if (!empty($searchKey)) {
                $searchValue = $this->get('search_value', '');
                if ($searchValue !== '') {
                    switch ($searchKey) {
                        case 'order_number':
                            $condition['order_number'] = $searchValue;
                            break;
                        case 'txn_id':
                            $orderId = (new PaypalBiz())->getByTxnId($this->shopId, $searchValue);
                            $orderId = $orderId ? $orderId['order_id'] : 0;
                            $condition['order_id'] = $orderId;
                            break;
                        case 'username':
                            $condition['customer_name_like'] = $searchValue;
                            break;
                        case 'email':
                            $condition['customer_email'] = $searchValue;
                            break;
                    }
                }
            } else {
                $startTime = $this->get('start_time', '');
                if ($startTime !== '') {
                    $condition['start_created_at'] = strtotime(date('Y-m-d 00：00：00', strtotime($startTime)));
                }
                $endTime = $this->get('end_time', '');
                if ($endTime !== '') {
                    $condition['end_created_at'] = strtotime(date('Y-m-d 23:59:59', strtotime($endTime)));
                }
                $orderFrom = $this->get('order_from', '');
                if ($orderFrom !== '') {
                    $condition['device_from'] = $orderFrom;
                }
                $orderType = $this->get('order_type', '');
                if ($orderType !== '') {
                    $condition['order_type'] = $orderType;
                }
                $paymentMethod = $this->get('payment_method', '');
                if ($paymentMethod !== '') {
                    $condition['payment_code'] = $paymentMethod;
                }
                $statusId = (int)$this->get('order_status', 0);
                if ($statusId > 0) {
                    $condition['order_status_id'] = $statusId;
                }
            }

            $page = $this->request->get['page'] ?? 1;
            $pageSize = $this->request->get['limit'] ?? 10;

            $orderList = $orderBiz->getOrderList($condition, [], (int)$page, (int)$pageSize);
            array_walk($orderList, function (&$orderInfo) use ($orderStatuses) {
                $orderInfo['order_status_text'] = $orderStatuses[$orderInfo['order_status_id']] ?? '';
            });

            return [
                'code' => 0,
                'count' => $orderBiz->count,
                'data' => $orderList
            ];
        }

        return $this->render([
            'order_statues' => $orderStatuses,
            'payment_list' => (new PaymentBiz())->getPaymentList($this->shopId)
        ]);
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
