<?php
/**
 * 店铺商品页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\ConfigHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class ProductController extends Controller
{
    public function category()
    {
        $prodBiz = new ProductBiz();

        $cateId = $this->get('cate_id', 0);
        $cateInfo = $prodBiz->getCategoryById($this->shopId, (int)$cateId);
        if (empty($cateInfo['category_status'])) {
            return $this->response->redirect('/page-not-found.html');
        }

        $cateInfo['description'] = $cateInfo['desc_list'][$this->langCode] ?? [];
        unset($cateInfo['desc_list']);

        $cateLevel = $prodBiz->getCateLevelByChildId($this->shopId, (int)$cateInfo['product_category_id'], $this->langCode);
        if (!empty($cateLevel)) {
            $cateLevel = array_reverse($cateLevel);
        }

        $cateIds = $prodBiz->getCategoryIds($this->shopId, (int)$cateInfo['product_category_id']);

        $where = [
            'shop_id' => $this->shopId,
            'product_status' => 1,
            'category_ids' => $cateIds,
            'language_code' => $this->langCode
        ];

        $sort = strtolower($this->get('sort', ''));
        $orderBy = ['is_sold_out' => 'asc', 'sort' => 'asc', 'product_id' => 'desc'];
        $pageLink = '/' . $cateInfo['category_url'] . '-c' . $cateId . '.html?';
        switch ($sort) {
            case 'price2low':
                $orderBy = ['is_sold_out' => 'asc', 'price' => 'desc', 'sort' => 'asc', 'product_id' => 'desc'];
                $pageLink .= 'sort=price2low&';
                break;
            case 'price2high':
                $orderBy = ['is_sold_out' => 'asc', 'price' => 'asc', 'sort' => 'asc', 'product_id' => 'desc'];
                $pageLink .= 'sort=price2high&';
                break;
            default:
                $sort = 'relevance';
                break;
        }

        $page = (int)$this->get('page', 1);
        $pageSize = (int)$cateInfo['product_show_size'];

        $prodList = $prodBiz->getProductList($where, $orderBy, $page, $pageSize);
        if (empty($prodList)) {
            return $this->response->redirect('/');
        }

        $prodList = $prodBiz->getProductAllByList($this->shopId, $this->warehouseCode, $prodList);

        $pageTotal = (int)ceil($prodBiz->count / $pageSize);
        $pageTotal = $pageTotal > 1 ? $pageTotal : 1;
        $page = $page > $pageTotal ? $pageTotal : $page;

        $sortList = [
            'relevance' => [
                'icon' => 'fa fa-thumbs-o-up',
                'text' => 'Relevance'
            ],
            'price2low' => [
                'icon' => 'fa fa-dollar',
                'text' => 'High to Low'
            ],
            'price2high' => [
                'icon' => 'fa fa-dollar',
                'text' => 'Low to High'
            ],
        ];

        $data = [
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'sort' => $sort,
            'sort_list' => $sortList,
            'cate_info' => $cateInfo,
            'cate_level' => $cateLevel,
            'prod_list' => $prodList,
            'page' => $page,
            'page_total' => $pageTotal,
            'page_link' => $pageLink
        ];

        return $this->render($data);
    }

    public function detail()
    {
        $prodId = $this->get('prod_id', 0);
        $prodId = (int)$prodId;

        $prodBiz = new ProductBiz();
        $prodInfo = $prodBiz->getProductById($this->shopId, $prodId);
        if (empty($prodInfo['product_status']) || empty($prodInfo['sku_list'])) {
            return $this->response->redirect('/page-not-found.html');
        }

        $prodSkuArr = array_keys($prodInfo['sku_list']);
        $prodInfo['desc'] = $prodInfo['desc_list'][$this->langCode] ?? [];
        unset($prodInfo['sku_list'], $prodInfo['desc_list']);

        $skuQtyPriceList = $prodBiz->getSkuQtyPriceListBySkuArr($this->shopId, $prodSkuArr, $this->warehouseCode);
        if (!empty($skuQtyPriceList)) {
            foreach ($skuQtyPriceList as $sku => $qtyPrice) {
                $price = (float)$qtyPrice['price'];
                $priceText = format_price($price, $this->currency, 1, true);
                $listPrice = (float)$qtyPrice['list_price'];
                $listPriceText = format_price($listPrice, $this->currency, 1, true);
                $priceOff = '';
                if($listPrice > $price){
                    $priceOff = ($listPrice - $price) / $listPrice * 100;
                    $priceOff = number_format($priceOff, 2, '.', '').'% OFF';
                }
                $skuQtyPriceList[$sku]['price_text'] = $priceText;
                $skuQtyPriceList[$sku]['list_price_text'] = $listPriceText;
                $skuQtyPriceList[$sku]['price_off'] = $priceOff;
            }
        }

        $skuImgList = $prodBiz->getSkuImageListBySkuArr($this->shopId, $prodSkuArr);

        $cateLevel = $prodBiz->getCateLevelByChildId($this->shopId, (int)$prodInfo['product_category_id'], $this->langCode);
        if (!empty($cateLevel)) {
            $cateLevel = array_reverse($cateLevel);
        }

        $data = [
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'prod_info' => $prodInfo,
            'sku_arr' => $prodSkuArr,
            'sku_qty_price_list' => $skuQtyPriceList,
            'sku_img_list' => $skuImgList,
            'cate_level' => $cateLevel,
            'cart_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'addToCart')
        ];

        return $this->render($data);
    }
}
