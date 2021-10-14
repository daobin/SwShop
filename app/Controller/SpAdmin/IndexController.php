<?php
/**
 * 店铺管理后台首页
 * User: dao bin
 * Date: 2021/7/14
 * Time: 15:59
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\AdminBiz;
use App\Biz\CustomerBiz;
use App\Biz\OrderBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        $start = strtotime(date('Y-m-d 00:00:00'));
        $end = strtotime(date('-m-d 23:59:59'));

        $customerBiz = new CustomerBiz($this->langCode);
        $customerBiz->getCustomerList(['shop_id' => $this->shopId, 'register_at_between' => [$start, $end]], [], 1, 1);

        $orderBiz = new OrderBiz();
        $orderBiz->getOrderList(['shop_id' => $this->shopId, 'created_at_between' => [$start, $end]], [], 1, 1);

        list($cnTime, $usTime, $ukTime) = get_world_times();

        return $this->render([
            'admin_name' => $this->operator,
            'today_customer_cnt' => $customerBiz->count,
            'today_order_cnt' => $orderBiz->count,
            'cn_time' => $cnTime,
            'us_time' => $usTime,
            'uk_time' => $ukTime
        ]);
    }

    public function dashboard()
    {
        $customerBiz = new CustomerBiz($this->langCode);
        $orderBiz = new OrderBiz();

        $customerCounts = [];
        $orderCounts = [];

        $orderTotals = [];
        $customerPrices = [];

        $days = [];
        $start = date('Y-m-d 00:00:00', strtotime('-1 month'));
        $start = strtotime($start);
        $end = time();

        $customerList = $customerBiz->getNewCustomerListByTime($this->shopId, $start, $end);
        if (!empty($customerList)) {
            foreach ($customerList as $idx => $customer) {
                $date = date('Y.m.d', $customer['registered_at']);
                if (isset($customerList[$date])) {
                    $customerList[$date]++;
                } else {
                    $customerList[$date] = 1;
                }
                unset($customerList[$idx]);
            }
        }

        $paymentOrderStatusIds = [
            get_order_status_id('pending'),
            get_order_status_id('in_process'),
            get_order_status_id('shipped')
        ];
        $orderList = $orderBiz->getNewOrderListByTime($this->shopId, $start, $end);
        if (!empty($orderList)) {
            foreach ($orderList as $idx => $order) {
                $orderStatusId = (int)$order['order_status_id'];
                $date = date('Y.m.d', $order['created_at']);
                if (isset($orderList[$date])) {
                    $orderList[$date]['count']++;
                    $orderList[$date]['total'] += (float)$order['default_currency_total'];
                    if (in_array($orderStatusId, $paymentOrderStatusIds)) {
                        $orderList[$date]['payment_count']++;
                        $orderList[$date]['payment_total'] += (float)$order['default_currency_total'];
                    }
                } else {
                    $orderList[$date] = [
                        'count' => 1,
                        'total' => (float)$order['default_currency_total'],
                        'payment_count' => in_array($orderStatusId, $paymentOrderStatusIds) ? 1 : 0,
                        'payment_total' => in_array($orderStatusId, $paymentOrderStatusIds) ? (float)$order['default_currency_total'] : 0,
                        'customer_ids' => []
                    ];
                }
                $customerId = $order['customer_id'];
                $orderList[$date]['customer_ids'][$customerId] = $customerId;
                unset($orderList[$idx]);
            }
        }

        $historyEnd = strtotime('-1 day', $start);
        $historyWhere = ['shop_id' => $this->shopId, 'register_at_between' => [0, $historyEnd]];
        $customerBiz->getCustomerList($historyWhere, [], 1, 1);

        $historyWhere = ['shop_id' => $this->shopId, 'created_at_between' => [0, $historyEnd]];
        $orderBiz->getOrderList($historyWhere, [], 1, 1);

        $todayDate = date('Y.m.d');
        $todayStatistics = [
            'register_customer' => 0,
            'order_customer' => 0,
            'order_count' => 0,
            'order_total' => 0,
            'customer_price' => 0,
            'payment_order_count' => 0,
            'payment_order_total' => 0,
            'payment_customer_price' => 0
        ];
        for ($idx = 0; $start <= $end; $idx++) {
            $days[$idx] = date('Y.m.d', $start);

            $customerCounts[$idx] = $customerCounts[$idx - 1] ?? $customerBiz->count;
            $customerCounts[$idx] += $customerList[$days[$idx]] ?? 0;

            $orderCounts[$idx] = $orderCounts[$idx - 1] ?? $orderBiz->count;
            $orderTotals[$idx] = 0;
            $customerPrices[$idx] = 0;
            if (!empty($orderList[$days[$idx]])) {
                $orderCounts[$idx] += $orderList[$days[$idx]]['count'];
                $orderTotals[$idx] = $orderList[$days[$idx]]['total'];
                $customerPrices[$idx] = $orderList[$days[$idx]]['total'] / $orderList[$days[$idx]]['count'];
            }

            if ($todayDate == $days[$idx]) {
                $todayStatistics = [
                    'register_customer' => $customerList[$days[$idx]] ?? 0,
                    'order_customer' => empty($orderList[$days[$idx]]['customer_ids']) ? 0 : count($orderList[$days[$idx]]['customer_ids']),
                    'order_count' => $orderList[$days[$idx]]['count'] ?? 0,
                    'order_total' => $orderList[$days[$idx]]['total'] ?? 0,
                    'customer_price' => 0,
                    'payment_order_count' => $orderList[$days[$idx]]['payment_count'] ?? 0,
                    'payment_order_total' => $orderList[$days[$idx]]['payment_total'] ?? 0,
                    'payment_customer_price' => 0
                ];
                if($todayStatistics['order_count'] > 0){
                    $todayStatistics['customer_price'] = $todayStatistics['order_total'] / $todayStatistics['order_count'];
                }
                if($todayStatistics['payment_order_count'] > 0){
                    $todayStatistics['payment_customer_price'] = $todayStatistics['payment_order_total'] / $todayStatistics['payment_order_count'];
                }
            }

            $start += 86400;
        }
        unset($customerList, $orderList);

        $countMax = max($customerCounts);
        if ($countMax < max($orderCounts)) {
            $countMax = max($orderCounts);
        }

        $countOption = [
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'cross'
                ]
            ],
            'xAxis' => [
                'type' => 'category',
                'data' => $days,
                'axisLabel' => [
                    'rotate' => 15
                ]
            ],
            'yAxis' => [
                'min' => 0,
                'max' => $countMax
            ],
            'series' => [
                [
                    'name' => '用户累计数',
                    'type' => 'line',
                    'smooth' => true,
                    'data' => $customerCounts
                ],
                [
                    'name' => '订单累计数',
                    'type' => 'line',
                    'smooth' => true,
                    'data' => $orderCounts
                ]
            ]
        ];

        $amountOption = [
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'cross'
                ]
            ],
            'xAxis' => [
                'type' => 'category',
                'data' => $days,
                'axisLabel' => [
                    'rotate' => 15
                ]
            ],
            'yAxis' => [
                'min' => floor(min($customerPrices)),
                'max' => ceil(max($orderTotals))
            ],
            'series' => [
                [
                    'name' => '订单总额',
                    'type' => 'bar',
                    'data' => $orderTotals
                ],
                [
                    'name' => '客单均价',
                    'type' => 'bar',
                    'data' => $customerPrices
                ]
            ]
        ];

        $currencySymbol = '';
        if ($this->currency) {
            $currencySymbol = $this->currency['symbol_left'] . $this->currency['symbol_right'];
        }

        return $this->render([
            'today_statistics' => $todayStatistics,
            'count_option' => $countOption,
            'amount_option' => $amountOption,
            'currency_symbol' => $currencySymbol
        ]);
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);
        return $this->render(['csrf_token' => $safeHelper->buildCsrfToken('BG', 'login')]);
    }

    public function loginProcess()
    {
        if (!$this->request->isPost) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $account = $this->post('account');
        $password = $this->post('password');
        if ($account == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_account', $this->langCode)];
        }
        if ($password == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_password', $this->langCode)];
        }

        $adminInfo = (new AdminBiz())->getAdminByAccount($this->shopId, $account);
        if (empty($adminInfo) || !password_verify($password, $adminInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_valid_account_password', $this->langCode)];
        }

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_admin_info', json_encode($adminInfo));
        $this->session->remove('BGlogin');
        return ['status' => 'success', 'url' => '/spadmin'];
    }

    public function logout()
    {
        $this->session->clear();
        $this->response->redirect('/spadmin/login.html');
    }
}
