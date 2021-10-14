<?php
/**
 * 邮件管理
 * User: dao bin
 * Date: 2021/10/13
 * Time: 11:05
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\EmailBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class EmailController extends Controller
{
    public function emailTpl()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new EmailBiz())->getEmailTemplateList($this->shopId)
            ];
        }

        return $this->render();
    }

    public function emailTplDetail()
    {
        if ($this->request->isPost) {
            return $this->emailTplSave();
        }

        $template = $this->get('template');
        $tplInfo = (new EmailBiz())->getEmailTemplateByTpl($this->shopId, $template);
        if (empty($tplInfo)) {
            return LanguageHelper::get('invalid_email_tpl', $this->langCode);
        }

        return $this->render([
            'tpl_info' => $tplInfo,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'emailtpl' . $template),
        ]);
    }

    private function emailTplSave()
    {
        $emailBiz = new EmailBiz();

        $subject = $this->post('subject');
        $template = $this->get('template');
        $tplInfo = $emailBiz->getEmailTemplateByTpl($this->shopId, $template);
        if (empty($tplInfo)) {
            return ['status' => 'fail', LanguageHelper::get('invalid_email_tpl', $this->langCode)];
        }

        $bannerImages = [];
        $save = $emailBiz->updateEmailTemplate($this->shopId, $template, $subject, $bannerImages, $this->operator);
        if($save > 0){
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }
}
