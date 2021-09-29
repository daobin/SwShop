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
use App\Biz\OrderBiz;
use App\Biz\ProductBiz;
use App\Controller\Controller;
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

    public function customerService(){
        return $this->render(['hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'ordertracking')]);
    }

    public function orderTracking()
    {
        $email = $this->post('email');
        $number = $this->post('number');

        $orderBiz = new OrderBiz();

        $orderInfo = [];
        $skuArr = [];
        $orderId = 0;
        if ($this->request->isPost) {
            $orderInfo = $orderBiz->getOrderForTracking($this->shopId, $email, $number);
            $skuArr = $orderInfo ? array_keys($orderInfo['prod_list']) : [];
            $orderId = $orderInfo['order_id'] ?? 0;
        }

        $prodImgList = (new ProductBiz())->getSkuImageListBySkuArr($this->shopId, $skuArr, true);
        $orderCurrency = (new CurrencyBiz())->getCurrencyByCode($this->shopId, ($orderInfo['currency_code'] ?? ''));

        return $this->render([
            'email' => $email,
            'number' => $number,
            'order_info' => $orderInfo,
            'prod_img_list' => $prodImgList,
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

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);

        $data = [
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
