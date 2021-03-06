<?php
/**
 * 商品类目管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\LanguageBiz;
use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class CategoryController extends Controller
{
    public function index()
    {
        $cateTreeList = [];
        $prodBiz = new ProductBiz();

        $langCodes = (new LanguageBiz())->getLangCodes($this->shopId);
        foreach ($langCodes as $idx => $langCode) {
            $cateTreeList[$idx] = [
                [
                    'id' => 0 - $idx - 1,
                    'title' => LanguageHelper::get('product_category', $langCode),
                    'spread' => true,
                    'children' => $prodBiz->getCategoryTree($this->shopId, 0, $langCode)
                ]
            ];
        }

        return $this->render(['lang_codes' => $langCodes, 'cate_tree_list' => $cateTreeList]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $cateId = $this->get('cate_id', 0);
        $cateId = (int)$cateId;
        if ($cateId < 0) {
            return LanguageHelper::get('invalid_request', $this->langCode);
        }

        $prodBiz = new ProductBiz();
        $cateTreeList = [
            [
                'category_name' => '顶级类目',
                'product_category_id' => 0,
                'children' => $prodBiz->getCategoryTree($this->shopId, 0, $this->langCode, $cateId)
            ]
        ];

        $cateInfo = $prodBiz->getCategoryById($this->shopId, $cateId);
        $cateDescList = $cateInfo['desc_list'] ?? [];
        unset($cateInfo['desc_list']);

        if (!empty($cateInfo)) {
            $parentId = $cateInfo['parent_id'];
        } else {
            $parentId = $this->get('parent_id', -1);
            $parentId = (int)$parentId > 0 ? (int)$parentId : 0;
        }

        return $this->render([
            'parent_id' => $parentId,
            'cate_info' => $cateInfo,
            'cate_desc_list' => $cateDescList,
            'cate_tree_list' => $cateTreeList,
            'lang_codes' => (new LanguageBiz())->getLangCodes($this->shopId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'cate_' . $cateId)
        ]);
    }

    private function save()
    {
        $cateId = $this->get('cate_id', 0);
        $cateId = (int)$cateId;
        if ($cateId < 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $parentId = $this->post('parent_id', -1);
        $parentId = (int)$parentId > 0 ? (int)$parentId : 0;
        $cateStatus = isset($this->request->post['cate_status']) ? 1 : 0;
        $cateSort = empty($this->request->post['cate_sort']) ? 0 : (int)$this->request->post['cate_sort'];
        $cateSort = $cateSort > 0 ? $cateSort : 0;
        $cateUrl = empty($this->request->post['cate_url']) ? '' : trim($this->request->post['cate_url']);
        $redirectLink = empty($this->request->post['redirect_link']) ? '' : trim($this->request->post['redirect_link']);
        $prodSize = empty($this->request->post['prod_size']) ? 0 : (int)$this->request->post['prod_size'];
        $prodSize = $prodSize > 0 ? $prodSize : 20;
        $reviewSize = empty($this->request->post['review_size']) ? 0 : (int)$this->request->post['review_size'];
        $reviewSize = $reviewSize > 0 ? $reviewSize : 0;

        $cateUrl = trim($cateUrl, '/');
        $cateUrl = process_url_string($cateUrl);
        if(empty($cateUrl)){
            return ['status' => 'fail', 'msg' => '请输入类目 URL'];
        }
        if (filter_var($cateUrl, FILTER_VALIDATE_URL)) {
            return ['status' => 'fail', 'msg' => '类目 URL 无效'];
        }
        if (!empty($redirectLink) && !filter_var($redirectLink, FILTER_VALIDATE_URL)) {
            return ['status' => 'fail', 'msg' => '跳转链接无效'];
        }

        if (empty($this->request->post['cate_name'][$this->langCode])) {
            return ['status' => 'fail', 'msg' => '默认语言[' . strtoupper($this->langCode) . ']的类目名称不能为空'];
        }

        $cateNameList = $this->request->post['cate_name'];
        $cateDescList = $this->request->post['cate_desc'];
        $cateDescMList = $this->request->post['cate_desc_m'];
        $metaTitleList = $this->request->post['meta_title'];
        $metaKeywordsList = $this->request->post['meta_keywords'];
        $metaDescList = $this->request->post['meta_desc'];

        $cateData = [
            'shop_id' => $this->shopId,
            'product_category_id' => $cateId,
            'parent_id' => $parentId,
            'category_url' => $cateUrl,
            'redirect_link' => $redirectLink,
            'sort' => $cateSort,
            'category_status' => $cateStatus,
            'product_show_size' => $prodSize,
            'review_show_size' => $reviewSize,
            'created_at' => time(),
            'created_by' => $this->operator,
            'updated_at' => time(),
            'updated_by' => $this->operator
        ];

        $cateDescData = [];
        foreach ($cateNameList as $langCode => $cateName) {
            // 其他语言未填写内容时，使用默认语言下对应的内容
            $cateDesc = $cateDescList[$langCode] ?? '';
            $cateDescM = $cateDescMList[$langCode] ?? '';
            $metaTitle = $metaTitleList[$langCode] ?? '';
            $metaKeywords = $metaKeywordsList[$langCode] ?? '';
            $metaDesc = $metaDescList[$langCode] ?? '';
            if ($langCode != $this->langCode) {
                $cateName = empty($cateName) ? $cateNameList[$this->langCode] : $cateName;
                $cateDesc = empty($cateDesc) ? $cateDescList[$this->langCode] : $cateDesc;
                $cateDescM = empty($cateDescM) ? $cateDescMList[$this->langCode] : $cateDescM;
                $metaTitle = empty($metaTitle) ? $metaTitleList[$this->langCode] : $metaTitle;
                $metaKeywords = empty($metaKeywords) ? $metaKeywordsList[$this->langCode] : $metaKeywords;
                $metaDesc = empty($metaDesc) ? $metaDescList[$this->langCode] : $metaDesc;
            }

            $cateDescM = empty($cateDescM) ? $cateDesc : $cateDescM;
            $metaTitle = empty($metaTitle) ? $cateName : $metaTitle;

            $cateDescData[$langCode] = [
                'shop_id' => $this->shopId,
                'product_category_id' => $cateId,
                'language_code' => $langCode,
                'category_name' => $cateName,
                'category_description' => $cateDesc,
                'category_description_m' => $cateDescM,
                'meta_title' => $metaTitle,
                'meta_keywords' => $metaKeywords,
                'meta_description' => $metaDesc,
                'created_at' => time(),
                'created_by' => $this->operator,
                'updated_at' => time(),
                'updated_by' => $this->operator
            ];
        }

        if ((new ProductBiz())->saveCategory($cateData, $cateDescData) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }
}
