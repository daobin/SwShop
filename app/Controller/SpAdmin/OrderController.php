<?php
/**
 * 订单管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\CurrencyBiz;
use App\Biz\OrderBiz;
use App\Biz\PaymentBiz;
use App\Biz\PaypalBiz;
use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
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
                $condition['created_at_between'] = [$startTime, $endTime];
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
        if ($this->request->isPost) {
            return $this->save();
        }

        $orderNumber = $this->request->get['order_number'] ?? '';
        if (empty($orderNumber)) {
            return LanguageHelper::get('invalid_request', $this->langCode);
        }

        $orderBiz = new OrderBiz();
        $orderInfo = $orderBiz->getOrderByNumber($this->shopId, $orderNumber);
        if (empty($orderInfo)) {
            return LanguageHelper::get('invalid_order', $this->langCode);
        }

        $prodImgList = (new ProductBiz())->getSkuImageListBySkuArr($this->shopId, array_keys($orderInfo['prod_list']), true);
        $orderCurrency = (new CurrencyBiz())->getCurrencyByCode($this->shopId, $orderInfo['currency_code']);

        $condition = [
            'shop_id' => $this->shopId,
            'customer_id' => $orderInfo['customer_id'],
        ];
        $orderList = $orderBiz->getOrderList($condition, [], 1, 500);
        $orderListCnt = $orderBiz->count;

        return $this->render([
            'order_info' => $orderInfo,
            'prod_img_list' => $prodImgList,
            'order_currency' => $orderCurrency,
            'order_statuses' => $orderBiz->getSysOrderStatuses('zh'),
            'history_list' => $orderBiz->getHistoryListByOrderId($this->shopId, $orderInfo['order_id']),
            'total_list' => $orderBiz->getTotalListByOrderId($this->shopId, $orderInfo['order_id']),
            'order_address' => $orderBiz->getAddressByOrderId($this->shopId, $orderInfo['order_id']),
            'paypal_info' => (new PaypalBiz())->getByOrderId($this->shopId, $orderInfo['order_id']),
            'order_list' => $orderList,
            'order_list_count' => $orderListCnt,
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'order_' . $orderNumber),
            'to_fixed' => $this->get('to_fixed'),
            'status_notes' => get_order_status_notes($this->langCode)
        ]);
    }

    private function save()
    {
        $orderNumber = $this->request->get['order_number'] ?? '';
        if (empty($orderNumber)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $orderBiz = new OrderBiz();
        $orderInfo = $orderBiz->getOrderByNumber($this->shopId, $orderNumber);
        if (empty($orderInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_order', $this->langCode)];
        }

        $statusId = $this->post('status_id', 0);
        $isShow = $this->post('is_show', 0) == 1;
        $comment = $this->post('comment');
        $comment = $comment != '' ? $comment : get_order_status_note((int)$statusId, $this->langCode);

        $save = $orderBiz->updateOrderStatusById($this->shopId, $orderInfo['order_id'], (int)$statusId, $comment, $isShow, $this->operator);
        if ($save) {
            return [
                'status' => 'success',
                'msg' => LanguageHelper::get('save_successfully', $this->langCode),
                'url' => '/spadmin/order/' . $orderNumber . '?to_fixed=hd-status-history'
            ];
        }
        return ['status' => 'fail', 'msg' => LanguageHelper::get('save_failed', $this->langCode)];
    }
}
