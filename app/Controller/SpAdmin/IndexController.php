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
        $todayTime = strtotime(date('Y-m-d'));

        $customerBiz = new CustomerBiz();


        $orderBiz = new OrderBiz();
        $orderBiz->getOrderList(['shop_id' => $this->shopId, 'start_created_at' => $todayTime], [], 1, 1);

        $defaultTime = date_default_timezone_get();
        date_default_timezone_set('Asia/Shanghai');
        $cnTime = date('Y-m-d H:i');
        date_default_timezone_set('America/New_York');
        $usTime = date('Y-m-d H:i');
        date_default_timezone_set('Europe/London');
        $ukTime = date('Y-m-d H:i');
        date_default_timezone_set($defaultTime);

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
        $customerCounts = [];
        $orderCounts = [];

        $orderTotals = [];
        $customerPrices = [];

        $days = [];
        $start = strtotime('-1 month');
        $end = time();
        for ($idx = 0; $start <= $end; $idx++) {
            $days[$idx] = date('Y.m.d', $start);
            $customerCounts[$idx] = $customerCounts[$idx - 1] ?? 0;
            $customerCounts[$idx] += mt_rand(10, 30);
            $orderCounts[$idx] = $orderCounts[$idx - 1] ?? 0;
            $orderCounts[$idx] += mt_rand(0, 50);
            $orderTotals[$idx] = mt_rand(150, 1000);
            $customerPrices[$idx] = mt_rand(10, 150);
            $start += 86400;
        }

        $countMin = min($customerCounts);
        if ($countMin > min($orderCounts)) {
            $countMin = min($orderCounts);
        }
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
                'min' => $countMin,
                'max' => $countMax
            ],
            'series' => [
                [
                    'name' => '用户',
                    'type' => 'line',
                    'smooth' => true,
                    'data' => $customerCounts
                ],
                [
                    'name' => '订单',
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
                'min' => min($customerPrices),
                'max' => max($orderTotals)
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

        return $this->render([
            'count_option' => $countOption,
            'amount_option' => $amountOption
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
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $account = $this->post('account');
        $password = $this->post('password');
        if ($account == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_account')];
        }
        if ($password == '') {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_password')];
        }

        $adminInfo = (new AdminBiz())->getAdminByAccount($this->shopId, $account);
        if (empty($adminInfo) || !password_verify($password, $adminInfo['password'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('enter_valid_account_password')];
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
