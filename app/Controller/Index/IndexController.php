<?php
/**
 * 店铺首页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\BannerBiz;
use App\Biz\ConfigBiz;
use App\Biz\CurrencyBiz;
use App\Biz\CustomerBiz;
use App\Biz\OrderBiz;
use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\EmailHelper;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        $loopBanner = (new BannerBiz())->getBannerByCode($this->shopId, 'index_main_loop');
        $loopBanner = empty($loopBanner['banner_status']) || empty($loopBanner['image_list']) ? [] : $loopBanner['image_list'];

        $featuredProds = (new ProductBiz())->getFeaturedProductList($this->shopId, $this->langCode, $this->warehouseCode);

        $data = [
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'loop_banner' => $loopBanner,
            'featured_prods' => $featuredProds,
            'index_bottom_text' => (new ConfigBiz())->getConfigByKey($this->shopId, 'INDEX_BOTTOM_TEXT')
        ];
        return $this->render($data);
    }

    public function customerService()
    {
        $this->session->set('login_to', '/customer-service.html');

        $customerInfo = $this->session->get('sp_customer_info');
        $customerInfo = $customerInfo ? json_decode($customerInfo, true) : [];
        $customerName = ($customerInfo['first_name'] ?? '') . ' ' . ($customerInfo['last_name'] ?? '');

        return $this->render([
            'customer_name' => trim($customerName),
            'customer_email' => $customerInfo['email'] ?? '',
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'ordertracking')
        ]);
    }

    public function orderTracking()
    {
        $email = $this->post('email');
        $number = $this->post('number');

        $orderBiz = new OrderBiz();

        $orderInfo = [];
        $skuArr = [];
        $prodIds = [];
        $orderId = 0;
        if ($this->request->isPost) {
            $orderInfo = $orderBiz->getOrderForTracking($this->shopId, $email, $number);
            if (!empty($orderInfo)) {
                $skuArr = array_keys($orderInfo['prod_list']);
                $prodIds = array_column($orderInfo['prod_list'], 'product_id', 'product_id');
                $orderId = $orderInfo['order_id'] ?? 0;
            }
        }

        $prodBiz = new ProductBiz();
        $prodImgList = $prodBiz->getProdImageListByProdIds($this->shopId, $prodIds, true);
        $skuAttrList = $prodBiz->getSkuAttrListBySkuArr($this->shopId, $skuArr);
        $orderCurrency = (new CurrencyBiz())->getCurrencyByCode($this->shopId, ($orderInfo['currency_code'] ?? ''));

        return $this->render([
            'email' => $email,
            'number' => $number,
            'order_info' => $orderInfo,
            'prod_img_list' => $prodImgList,
            'sku_attr_list' => $skuAttrList,
            'order_currency' => $orderCurrency,
            'order_statuses' => $orderBiz->getSysOrderStatuses($this->langCode),
            'history_list' => $orderBiz->getHistoryListByOrderId($this->shopId, $orderId),
            'total_list' => $orderBiz->getTotalListByOrderId($this->shopId, $orderId),
            'order_address' => $orderBiz->getAddressByOrderId($this->shopId, $orderId),
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'is_post' => $this->request->isPost,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'ordertracking'),
        ]);
    }

    public function forgotPassword()
    {
        $email = $this->session->get('forgot_password_email', '');
        $this->session->remove('forgot_password_email');

        $error = $this->session->get('forgot_password_error', '');
        $this->session->remove('forgot_password_error');

        if ($this->request->isPost) {
            $email = $this->post('email');
            $this->session->set('forgot_password_email', $email);

            $token = (new CustomerBiz($this->langCode))->buildForgotPasswordToken($this->shopId, $email);
            if (empty($token)) {
                $this->session->set('forgot_password_error', LanguageHelper::get('email_invalid', $this->langCode));
            } else {
                \Swoole\Event::defer(function () use ($email, $token) {
                    $mailData = [
                        'template' => 'forgot_password',
                        'to_address' => $email,
                        'submission_time' => date('Y-m-d H:i:s'),
                        'forgot_change_link' => 'http://' . $this->host . '/reset-password.html?token=' . $token
                    ];
                    $mailed = (new EmailHelper($this->shopId, $this->host))->sendMail($mailData);
                    add_log('mail', ['mail' => $mailData['template'], 'res' => $mailed]);
                });
            }

            $this->response->redirect('/forgot-password.html');
        }

        return $this->render([
            'email' => $email,
            'error' => $error,
            'is_post' => $this->request->isPost,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'forgotpwd'),
        ]);
    }

    public function resetPassword()
    {
        $customerBiz = new CustomerBiz($this->langCode);

        $token = $this->get('token');
        $tokenInfo = $customerBiz->getForgotPasswordByToken($this->shopId, $token);
        if (empty($tokenInfo)) {
            return $this->render(['error' => LanguageHelper::get('invalid_link', $this->langCode)]);
        }

        if ($tokenInfo['status'] != 0) {
            return $this->render(['error' => LanguageHelper::get('invalid_link', $this->langCode)]);
        }

        if (time() >= $tokenInfo['expired']) {
            $customerBiz->updateForgotPasswordStatus($this->shopId, $tokenInfo['forgot_password_id'], 2);
            return $this->render(['error' => LanguageHelper::get('invalid_link', $this->langCode)]);
        }

        $email = $tokenInfo['email'];
        $emailCustomerInfo = $customerBiz->getCustomerByEmail($this->shopId, $email);
        if (empty($emailCustomerInfo)) {
            return $this->render(['error' => LanguageHelper::get('invalid_link', $this->langCode)]);
        }

        $error = '';
        if ($this->request->isPost) {
            $password = $this->post('password');
            $password2 = $this->post('password2');
            if (empty($password) || $password !== $password2) {
                return $this->render(['email' => $email, 'error' => LanguageHelper::get('pwd_invalid', $this->langCode)]);
            }

            $reset = $customerBiz->updatePassword($this->shopId, $emailCustomerInfo['customer_id'], $password, '');
            if ($reset > 0) {
                \Swoole\Event::defer(function () use ($email) {
                    $mailData = [
                        'template' => 'password_success',
                        'to_address' => $email
                    ];
                    $mailed = (new EmailHelper($this->shopId, $this->host))->sendMail($mailData);
                    add_log('mail', ['mail' => $mailData['template'], 'res' => $mailed]);
                });

                $customerBiz->updateForgotPasswordStatus($this->shopId, $tokenInfo['forgot_password_id'], 1);
                $this->session->set('password_reset_success', 1);
                $this->session->remove('sp_customer_info');
                $this->response->redirect('/login.html');
            } else {
                $email = '';
                $error = LanguageHelper::get('password_reset_fail_tip', $this->langCode);
                $customerBiz->updateForgotPasswordStatus($this->shopId, $tokenInfo['forgot_password_id'], 3);
            }
        }

        return $this->render([
            'email' => $email,
            'error' => $error,
            'is_post' => $this->request->isPost,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'resetpwd'),
        ]);
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);
        $passwordResetSuccess = $this->session->get('password_reset_success', 0);
        $this->session->remove('password_reset_success');

        $data = [
            'password_reset_success' => $passwordResetSuccess,
            'register_tk' => $safeHelper->buildCsrfToken('IDX', 'register'),
            'login_tk' => $safeHelper->buildCsrfToken('IDX', 'login'),
        ];
        return $this->render($data);
    }

    public function logout()
    {
        $this->session->clear();
        return $this->response->redirect('/login.html');
    }

    public function pageNotFound()
    {
        $this->response->status(404);
        return $this->render();
    }
}
