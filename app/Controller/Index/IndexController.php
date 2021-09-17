<?php
/**
 * 店铺首页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\BannerBiz;
use App\Biz\ConfigBiz;
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

    public function orderTracking()
    {
        $orderInfo = [];
        if ($this->request->isPost) {
            $orderInfo = (new OrderBiz())->getOrderForTracking($this->shopId, $this->post('email'), $this->post('number'));
        }

        return $this->render([
            'order_info' => $orderInfo,
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
