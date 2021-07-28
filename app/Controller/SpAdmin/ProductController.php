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
use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class ProductController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $prodList = [];

            return [
                'code' => 0,
                'count' => count($prodList),
                'data' => $prodList
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $prodId = $this->request->get['prod_id'] ?? 0;
        $prodId = (int)$prodId;

        $prodBiz = new ProductBiz();
        $prodInfo = $prodBiz->getProductById($this->request->shop_id, $prodId);
        $prodDescList = $prodInfo['desc_list'] ?? [];
        unset($prodInfo['desc_list']);

        $cfgBiz = new ConfigBiz();
        $weightUnits = $cfgBiz->getConfigByKey($this->request->shop_id, 'WEIGHT_UNIT');
        $weightUnits = $weightUnits['config_value'] ?? [];
        $sizeUnits = $cfgBiz->getConfigByKey($this->request->shop_id, 'SIZE_UNIT');
        $sizeUnits = $sizeUnits['config_value'] ?? [];
        $warehouses = $cfgBiz->getConfigByKey($this->request->shop_id, 'WAREHOUSE');
        $warehouses = $warehouses['config_value'] ?? [];

        return $this->render([
            'prod_info' => $prodInfo,
            'prod_desc_list' => $prodDescList,
            'weight_units' => $weightUnits,
            'size_units' => $sizeUnits,
            'warehouses' => $warehouses,
            'cate_tree_list' => $prodBiz->getCategoryTree($this->request->shop_id, 0, reset($this->langCodes)),
            'lang_codes' => $this->langCodes,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'prod_' . $prodId)
        ]);
    }

    private function save()
    {
        $prodId = $this->request->get['prod_id'] ?? 0;
        $prodId = (int)$prodId;
        if ($prodId < 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $cateId = empty($this->request->post['category_id']) ? 0 : (int)$this->request->post['category_id'];
        $prodSort = empty($this->request->post['prod_sort']) ? 0 : (int)$this->request->post['prod_sort'];
        $prodSort = $prodSort > 0 ? $prodSort : 0;
        $prodStatus = empty($this->request->post['prod_status']) ? 0 : (int)$this->request->post['prod_status'];
        $prodStatus = in_array($prodStatus, [0, 1, 2]) ? $prodStatus : 0;
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

        if ($cateId <= 0) {
            return ['status' => 'fail', 'msg' => '请选择商品分类'];
        }
        if ($weight > 0 && empty($weightUnit)) {
            return ['status' => 'fail', 'msg' => '请选择重量单位'];
        }
        if (($length > 0 || $width > 0 || $height > 0) && empty($sizeUnit)) {
            return ['status' => 'fail', 'msg' => '请选择尺寸单位'];
        }

        $defaultLangCode = reset($this->langCodes);
        if (empty($this->request->post['prod_name'][$defaultLangCode])) {
            return ['status' => 'fail', 'msg' => '默认语言[' . strtoupper($defaultLangCode) . ']的商品名称不能为空'];
        }

        $prodNameList = $this->request->post['prod_name'];
        $prodDescList = $this->request->post['prod_desc'];
        $prodDescMList = $this->request->post['prod_desc_m'];
        $metaTitleList = $this->request->post['meta_title'];
        $metaKeywordsList = $this->request->post['meta_keywords'];
        $metaDescList = $this->request->post['meta_desc'];

        $prodData = [
            'shop_id' => $this->request->shop_id,
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
            'created_at' => time(),
            'created_by' => $this->spAdminInfo['account'] ?? '--',
            'updated_at' => time(),
            'updated_by' => $this->spAdminInfo['account'] ?? '--'
        ];

        $prodDescData = [];
        foreach ($prodNameList as $langCode => $prodName) {
            // 其他语言未填写内容时，使用默认语言下对应的内容
            $prodDesc = $prodDescList[$langCode] ?? '';
            $prodDescM = $prodDescMList[$langCode] ?? '';
            $metaTitle = $metaTitleList[$langCode] ?? '';
            $metaKeywords = $metaKeywordsList[$langCode] ?? '';
            $metaDesc = $metaDescList[$langCode] ?? '';
            if ($langCode != $defaultLangCode) {
                $prodName = empty($prodName) ? $prodNameList[$defaultLangCode] : $prodName;
                $prodDesc = empty($prodDesc) ? $prodDescList[$defaultLangCode] : $prodDesc;
                $prodDescM = empty($prodDescM) ? $prodDescMList[$defaultLangCode] : $prodDescM;
                $metaTitle = empty($metaTitle) ? $metaTitleList[$defaultLangCode] : $metaTitle;
                $metaKeywords = empty($metaKeywords) ? $metaKeywordsList[$defaultLangCode] : $metaKeywords;
                $metaDesc = empty($metaDesc) ? $metaDescList[$defaultLangCode] : $metaDesc;
            }

            $prodDescM = empty($prodDescM) ? $prodDesc : $prodDescM;
            $metaTitle = empty($metaTitle) ? $prodName : $metaTitle;

            $prodDescData[$langCode] = [
                'shop_id' => $this->request->shop_id,
                'product_id' => $prodId,
                'language_code' => $langCode,
                'product_name' => $prodName,
                'product_description' => $prodDesc,
                'product_description_m' => $prodDescM,
                'meta_title' => $metaTitle,
                'meta_keywords' => $metaKeywords,
                'meta_description' => $metaDesc,
                'created_at' => time(),
                'created_by' => $this->spAdminInfo['account'] ?? '--',
                'updated_at' => time(),
                'updated_by' => $this->spAdminInfo['account'] ?? '--'
            ];
        }

        if ((new ProductBiz())->saveProduct($prodData, $prodDescData) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }
}
