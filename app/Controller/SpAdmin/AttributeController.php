<?php
/**
 * 商品属性管理
 * User: dao bin
 * Date: 2021/8/31
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\LanguageBiz;
use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class AttributeController extends Controller
{
    public function group()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new ProductBiz())->getAttrGroupList($this->shopId, $this->langCode)
            ];
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'attr')
        ]);
    }

    public function groupDetail()
    {
        if ($this->request->isPost) {
            return $this->groupSave();
        }

        $groupId = (int)$this->get('group_id', 0);
        $groupInfo = (new ProductBiz())->getAttrGroupById($this->shopId, $groupId);

        $data = [
            'group_desc_list' => $groupInfo['desc_list'] ?? [],
            'lang_codes' => (new LanguageBiz())->getLangCodes($this->shopId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'attr' . $groupId)
        ];

        return $this->render($data);
    }

    private function groupSave()
    {
        $prodBize = new ProductBiz();

        $groupId = (int)$this->get('group_id', 0);
        $groupNames = $this->post('group_names');
        if(empty(reset($groupNames))){
            return ['status' => 'fail', 'msg' => '请输入属性组名称'];
        }

        foreach($groupNames as $langCode => $groupName){
            $nameGroupId = $prodBize->getAttrGroupIdByName($this->shopId, $langCode, $groupName);
            if($nameGroupId > 0 && $nameGroupId != $groupId){
                return ['status' => 'fail', 'msg' => '语言['.strtoupper($langCode).']下该属性组名称已存在'];
            }
        }

        $data = [
            'shop_id' => $this->shopId,
            'group_id' => $groupId,
            'group_names' => $groupNames,
            'operator' => $this->operator
        ];

        if ($prodBize->saveAttrGroup($data) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function value()
    {
        $groupId = (int)$this->get('group_id', 0);
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new ProductBiz())->getAttrValueList($this->shopId, $groupId, $this->langCode)
            ];
        }

        return $this->render([
            'group_id' => $groupId,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'attr')
        ]);
    }

    public function valueDetail()
    {
        if ($this->request->isPost) {
            return $this->valueSave();
        }

        $groupId = (int)$this->get('group_id', 0);
        $attrId = (int)$this->get('attr_id', 0);

        $prodBiz = new ProductBiz();
        $groupInfo = $prodBiz->getAttrGroupById($this->shopId, $groupId);
        if(empty($groupInfo['desc_list'])){
            return '属性组无效';
        }

        $attrInfo = $prodBiz->getAttrValueById($this->shopId, $attrId);

        $data = [
            'group_name' => reset($groupInfo['desc_list']),
            'value_desc_list' => $attrInfo['desc_list'] ?? [],
            'lang_codes' => (new LanguageBiz())->getLangCodes($this->shopId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'attr' . $attrId)
        ];

        return $this->render($data);
    }

    private function valueSave()
    {
        $prodBize = new ProductBiz();

        $groupId = (int)$this->get('group_id', 0);
        $attrId = (int)$this->get('attr_id', 0);
        $valueNames = $this->post('value_names');

        $prodBiz = new ProductBiz();
        $groupInfo = $prodBiz->getAttrGroupById($this->shopId, $groupId);
        if(empty($groupInfo['desc_list'])){
            return ['status' => 'fail', 'msg' => '属性组无效'];
        }

        foreach($valueNames as $langCode => $valueName){
            $nameAttrId = $prodBize->getAttrValueIdByName($this->shopId, $langCode, $valueName);
            if($nameAttrId > 0 && $nameAttrId != $attrId){
                return ['status' => 'fail', 'msg' => '语言['.strtoupper($langCode).']下该属性值已存在'];
            }
        }

        $data = [
            'shop_id' => $this->shopId,
            'group_id' => $groupId,
            'attr_id' => $attrId,
            'value_names' => $valueNames,
            'operator' => $this->operator
        ];

        if ($prodBize->saveAttrValue($data) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }
}
