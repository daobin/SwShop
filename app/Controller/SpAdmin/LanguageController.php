<?php
/**
 * 语言管理
 * User: dao bin
 * Date: 2021/8/25
 * Time: 13:40
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\LanguageBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class LanguageController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new LanguageBiz())->getLanguageList($this->shopId)
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'language')
        ];
        return $this->render($data);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $code = $this->get('code', '', 'trim,strtolower');

        $langBiz = new LanguageBiz();
        $langInfo = $langBiz->getLanguageByCode($this->shopId, $code);

        $data = [
            'language_code' => $code,
            'sort' => $langInfo['sort'] ?? '',
            'languages' => $langBiz->getSysLanguages(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $code)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $origCode = $this->get('code', '', 'trim,strtolower');
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '请选择语言'];
        }

        $langBiz = new LanguageBiz();
        if ($origCode !== $code && $langBiz->getLanguageByCode($this->shopId, $code)) {
            return ['status' => 'fail', 'msg' => '语言已存在'];
        }

        $sysLang = $langBiz->getSysLanguageByCode($code);
        if (empty($sysLang)) {
            return ['status' => 'fail', 'msg' => '语言无效'];
        }

        $data = [
            'language_code' => $code,
            'language_name' => $sysLang['language_name'],
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($langBiz->saveLanguage($this->shopId, $origCode, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $code = $this->post('code', '', 'trim,strtolower');
        if (empty($code)) {
            return ['status' => 'fail', 'msg' => '币种无效'];
        }

        if ((new LanguageBiz())->delLanguageByCode($this->shopId, $code)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
