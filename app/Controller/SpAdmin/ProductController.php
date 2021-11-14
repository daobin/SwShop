<?php
/**
 * 商品管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\ConfigBiz;
use App\Biz\LanguageBiz;
use App\Biz\ProductBiz;
use App\Biz\UploadBiz;
use App\Biz\WarehouseBiz;
use App\Controller\Controller;
use App\Helper\ConfigHelper;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class ProductController extends Controller
{
    public function index()
    {
        $prodBiz = new ProductBiz();

        if ($this->request->isAjax) {
            $condition = [
                'shop_id' => $this->shopId,
                'language_code' => $this->langCode
            ];

            $cateId = $this->get('category_id');;
            if (is_numeric($cateId)) {
                $condition['product_category_id'] = $cateId;
            }

            $prodStatus = $this->get('prod_status');
            if (is_numeric($prodStatus)) {
                $condition['product_status'] = $prodStatus;
            }

            $page = $this->request->get['page'] ?? 1;
            $pageSize = $this->request->get['limit'] ?? 10;
            $prodList = $prodBiz->getProductList($condition, [], (int)$page, (int)$pageSize);

            return [
                'code' => 0,
                'count' => $prodBiz->count,
                'data' => $prodList
            ];
        }

        $currencySymbol = '';
        if($this->currency){
            $currencySymbol = $this->currency['symbol_left'].$this->currency['symbol_right'];
        }

        return $this->render([
            'cate_tree_list' => $prodBiz->getCategoryTree($this->shopId, 0, $this->langCode),
            'product_status_arr' => ConfigHelper::get('product.product_status'),
            'currency_symbol' => $currencySymbol
        ]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $prodId = $this->get('prod_id', 0);
        $prodId = (int)$prodId;

        $prodBiz = new ProductBiz();
        $prodInfo = $prodBiz->getProductById($this->shopId, $prodId);
        $skuArr = empty($prodInfo['sku_list']) ? [] : array_keys($prodInfo['sku_list']);
        $prodDescList = $prodInfo['desc_list'] ?? [];
        $qtyPriceList = $prodBiz->getSkuQtyPriceListBySkuArr($this->shopId, $skuArr);
        $imageList = $prodBiz->getSkuImageListBySkuArr($this->shopId, $skuArr);

        $cfgBiz = new ConfigBiz();
        $weightUnits = $cfgBiz->getConfigByKey($this->shopId, 'WEIGHT_UNIT');
        $weightUnits = $weightUnits['config_value'] ?? [];
        $sizeUnits = $cfgBiz->getConfigByKey($this->shopId, 'SIZE_UNIT');
        $sizeUnits = $sizeUnits['config_value'] ?? [];

        $warehouses = (new WarehouseBiz())->getWarehouseList($this->shopId);
        $warehouses = $warehouses ? array_column($warehouses, 'warehouse_name', 'warehouse_code') : ['-' => '无仓库模式'];

        return $this->render([
            'prod_info' => $prodInfo,
            'prod_desc_list' => $prodDescList,
            'qty_price_list' => $qtyPriceList,
            'image_list' => $imageList,
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
            'weight_units' => $weightUnits,
            'size_units' => $sizeUnits,
            'warehouses' => $warehouses,
            'cate_tree_list' => $prodBiz->getCategoryTree($this->shopId, 0, $this->langCode),
            'upload_folders' => (new UploadBiz())->getFolderArr($this->shopId),
            'lang_codes' => (new LanguageBiz())->getLangCodes($this->shopId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'prod_' . $prodId)
        ]);
    }

    private function save()
    {
        $prodId = $this->request->get['prod_id'] ?? 0;
        $prodId = (int)$prodId;
        if ($prodId < 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        // 商品基础信息
        $cateId = empty($this->request->post['category_id']) ? 0 : (int)$this->request->post['category_id'];
        $prodSort = empty($this->request->post['prod_sort']) ? 0 : (int)$this->request->post['prod_sort'];
        $prodSort = $prodSort > 0 ? $prodSort : 0;
        $prodStatus = empty($this->request->post['prod_status']) ? 0 : (int)$this->request->post['prod_status'];
        $prodStatus = in_array($prodStatus, array_keys(ConfigHelper::get('product.product_status'))) ? $prodStatus : 0;
        $prodUrl = empty($this->request->post['prod_url']) ? '' : trim($this->request->post['prod_url']);
        $weight = empty($this->request->post['weight']) ? 0 : (float)$this->request->post['weight'];
        $weight = $weight > 0 ? $weight : 0;
        $weightUnit = empty($this->request->post['weight_unit']) ? '' : trim($this->request->post['weight_unit']);
        $length = empty($this->request->post['length']) ? 0 : (float)$this->request->post['length'];
        $length = $length > 0 ? $length : 0;
        $width = empty($this->request->post['width']) ? 0 : (float)$this->request->post['width'];
        $width = $width > 0 ? $width : 0;
        $height = empty($this->request->post['height']) ? 0 : (float)$this->request->post['height'];
        $height = $height > 0 ? $height : 0;
        $sizeUnit = empty($this->request->post['size_unit']) ? '' : trim($this->request->post['size_unit']);
        $ossAccessHost = (new OssHelper($this->shopId))->accessHost;

        if ($cateId <= 0) {
            return ['status' => 'fail', 'msg' => '请选择商品分类'];
        }
        $prodUrl = trim($prodUrl, '/');
        $prodUrl = process_url_string($prodUrl);
        if (filter_var($prodUrl, FILTER_VALIDATE_URL)) {
            return ['status' => 'fail', 'msg' => '商品 URL 无效'];
        }
        if ($weight > 0 && empty($weightUnit)) {
            return ['status' => 'fail', 'msg' => '请选择重量单位'];
        }
        if (($length > 0 || $width > 0 || $height > 0) && empty($sizeUnit)) {
            return ['status' => 'fail', 'msg' => '请选择尺寸单位'];
        }

        $time = time();
        $prodData = [
            'shop_id' => $this->shopId,
            'product_id' => $prodId,
            'product_category_id' => $cateId,
            'product_status' => $prodStatus,
            'product_url' => $prodUrl,
            'sort' => $prodSort,
            'weight' => $weight,
            'weight_unit' => $weightUnit,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'size_unit' => $sizeUnit,
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ];

        // 商品SKU信息
        $skuData = [];
        if (!empty($this->request->post['sku_data'])) {
            foreach ($this->request->post['sku_data'] as $datum) {
                $sku = trim($datum['sku'] ?? '');
                $sku = strtoupper($sku);
                if (empty($sku)) {
                    continue;
                }

                // 库存与价格
                $qtyPriceData = [];
                if (!empty($datum['warehouse']) && is_array($datum['warehouse']) && !empty(reset($datum['warehouse']))) {
                    foreach ($datum['warehouse'] as $warehouse) {
                        $qty = empty($datum['qty'][$warehouse]) ? 0 : (int)$datum['qty'][$warehouse];
                        $price = empty($datum['price'][$warehouse]) ? 0 : (float)$datum['price'][$warehouse];
                        $listPrice = empty($datum['list_price'][$warehouse]) ? 0 : (float)$datum['list_price'][$warehouse];

                        if ($price <= 0) {
                            return ['status' => 'fail', 'msg' => 'SKU [' . $sku . '] 销售价必须大于0'];
                        }
                        if ($price > $listPrice && $listPrice > 0) {
                            return ['status' => 'fail', 'msg' => 'SKU [' . $sku . '] 销售价不能大于市场价'];
                        }

                        $warehouse = strtoupper($warehouse);
                        $qtyPriceData[$warehouse] = [
                            'shop_id' => $this->shopId,
                            'product_id' => $prodId,
                            'sku' => $sku,
                            'warehouse_code' => $warehouse,
                            'qty' => $qty,
                            'price' => $price,
                            'list_price' => $listPrice,
                            'created_at' => $time,
                            'created_by' => $this->operator,
                            'updated_at' => $time,
                            'updated_by' => $this->operator
                        ];
                    }
                } else {
                    return ['status' => 'fail', 'msg' => 'SKU [' . $sku . '] 库存&价格无效'];
                }

                // 商品图片
                $imgData = [];
                if (!empty($datum['image']) && is_array($datum['image']) && !empty(reset($datum['image']))) {
                    $imgSort = 0;
                    foreach ($datum['image'] as $image) {
                        $imageName = explode('?', basename($image));
                        $imageName = preg_replace('/_\d+_\d+/', '_d_d', reset($imageName));

                        $imgData[] = [
                            'shop_id' => $this->shopId,
                            'sku' => $sku,
                            'image_path' => str_replace($ossAccessHost, '', dirname($image)),
                            'image_name' => $imageName,
                            'sort' => $imgSort++,
                            'created_at' => $time,
                            'created_by' => $this->operator,
                            'updated_at' => $time,
                            'updated_by' => $this->operator
                        ];
                    }
                } else {
                    return ['status' => 'fail', 'msg' => 'SKU [' . $sku . '] 图片无效'];
                }

                $skuData[$sku] = [
                    'qty_price_data' => $qtyPriceData,
                    'img_data' => $imgData
                ];
            }
        }

        if (empty($skuData)) {
            return ['status' => 'fail', 'msg' => '请添加SKU'];
        }

        // 判断是否已存在SKU
        $prodBiz = new ProductBiz();
        $existSkuList = $prodBiz->getProdSkuListBySkuArr($this->shopId, array_keys($skuData), $prodId);
        if (!empty($existSkuList)) {
            return ['status' => 'fail', 'msg' => '已存在SKU [' . implode(', ', array_keys($existSkuList)) . ']'];
        }

        // 商品描述信息
        if (empty($this->request->post['prod_name'][$this->langCode])) {
            return ['status' => 'fail', 'msg' => '默认语言 [' . strtoupper($this->langCode) . '] 的商品名称不能为空'];
        }

        $prodNameList = $this->request->post['prod_name'];
        $prodDescList = $this->request->post['prod_desc'];
        $prodDescMList = $this->request->post['prod_desc_m'];
        $metaTitleList = $this->request->post['meta_title'];
        $metaKeywordsList = $this->request->post['meta_keywords'];
        $metaDescList = $this->request->post['meta_desc'];

        $prodDescData = [];
        foreach ($prodNameList as $langCode => $prodName) {
            // 其他语言未填写内容时，使用默认语言下对应的内容
            $prodDesc = $prodDescList[$langCode] ?? '';
            $prodDescM = $prodDescMList[$langCode] ?? '';
            $metaTitle = $metaTitleList[$langCode] ?? '';
            $metaKeywords = $metaKeywordsList[$langCode] ?? '';
            $metaDesc = $metaDescList[$langCode] ?? '';
            if ($langCode != $this->langCode) {
                $prodName = empty($prodName) ? $prodNameList[$this->langCode] : $prodName;
                $prodDesc = empty($prodDesc) ? $prodDescList[$this->langCode] : $prodDesc;
                $prodDescM = empty($prodDescM) ? $prodDescMList[$this->langCode] : $prodDescM;
                $metaTitle = empty($metaTitle) ? $metaTitleList[$this->langCode] : $metaTitle;
                $metaKeywords = empty($metaKeywords) ? $metaKeywordsList[$this->langCode] : $metaKeywords;
                $metaDesc = empty($metaDesc) ? $metaDescList[$this->langCode] : $metaDesc;
            }else if (empty($prodUrl)) {
                // 默认URL为商品名称
                $prodUrl = process_url_string($prodName);
            }

            $prodDescM = empty($prodDescM) ? $prodDesc : $prodDescM;
            $metaTitle = empty($metaTitle) ? $prodName : $metaTitle;

            $prodDescData[$langCode] = [
                'shop_id' => $this->shopId,
                'product_id' => $prodId,
                'language_code' => $langCode,
                'product_name' => $prodName,
                'product_description' => $prodDesc,
                'product_description_m' => $prodDescM,
                'meta_title' => $metaTitle,
                'meta_keywords' => $metaKeywords,
                'meta_description' => $metaDesc,
                'created_at' => time(),
                'created_by' => $this->operator,
                'updated_at' => time(),
                'updated_by' => $this->operator
            ];
        }

        if ($prodBiz->saveProduct($prodData, $prodDescData, $skuData) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }
}
