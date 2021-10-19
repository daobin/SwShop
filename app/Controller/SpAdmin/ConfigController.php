<?php
/**
 * 店铺配置
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\ConfigBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class ConfigController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $grp = $this->request->get['cfg_grp'] ?? 'web_info';
            $cfgList = (new ConfigBiz())->getConfigListByGroup($this->shopId, $grp);

            if (!empty($cfgList)) {
                foreach ($cfgList as &$cfgInfo) {
                    $cfgInfo['config_value'] = $cfgInfo['config_value'] ?? '';
                    if($cfgInfo['config_key'] == 'TIMEZONE'){
                        $timezones = get_timezones();
                        $cfgInfo['config_value'] = $timezones[$cfgInfo['config_value']] ?? $cfgInfo['config_value'];
                    }
                    switch (strtolower($cfgInfo['value_type'])) {
                        case 'password':
                            $cfgInfo['config_value'] = hide_chars($cfgInfo['config_value']);
                            break;
                        case 'list':
                            $cfgInfo['config_value'] = empty($cfgInfo['config_value']) ? '' : array_keys($cfgInfo['config_value']);
                            break;
                    }
                }
            }

            return [
                'code' => 0,
                'data' => $cfgList
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $cfgKey = $this->request->get['cfg_key'] ?? '';
        $cfgKey = strtoupper($cfgKey);
        if (empty($cfgKey)) {
            return LanguageHelper::get('invalid_request', $this->langCode);
        }

        $cfgInfo = (new ConfigBiz())->getConfigByKey($this->shopId, $cfgKey);
        if (empty($cfgInfo)) {
            return LanguageHelper::get('invalid_request', $this->langCode);
        }

        $cfgInfo['csrf_token'] = (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $cfgKey);
        if ($cfgInfo['value_type'] == 'image') {
            $cfgInfo['oss_access_host'] = (new OssHelper($this->shopId))->accessHost;
        }
        return $this->render($cfgInfo);
    }

    private function save()
    {
        $cfgKey = $this->request->get['cfg_key'] ?? '';
        $cfgKey = strtoupper($cfgKey);
        if (empty($cfgKey)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $cfgBiz = new ConfigBiz();

        $cfgVal = trim($this->request->post['config_value'] ?? '');
        $valType = trim($this->request->post['value_type'] ?? '');

        switch (strtolower($valType)) {
            case 'password':
                $cfgVal = SafeHelper::encodeString($cfgVal);
                break;
            case 'int':
                $cfgVal = (int)$cfgVal;
                break;
            case 'list':
                $resetVal = [];
                $cfgVal = trim($cfgVal, ',');
                if (!empty($cfgVal)) {
                    $cfgVal = explode(',', $cfgVal);
                    foreach ($cfgVal as $val) {
                        $val = trim($val);
                        if (empty($val)) {
                            continue;
                        }

                        $val = explode('=', $val, 2);
                        if (count($val) == 2) {
                            $resetVal[$val[0]] = $val[1];
                        } else {
                            $resetVal[$val[0]] = $val[0];
                        }
                    }
                }
                $cfgVal = json_encode($resetVal);
                break;
        }

        switch ($cfgKey) {
            case 'WEBSITE_LOGO':
                $fileInfo = $this->request->files['file'] ?? [];
                $checked = (new SafeHelper($this->request, $this->response))->chkUploadImage($fileInfo, 'sp_' . $this->shopId . '/logo');
                if (isset($checked['status']) && $checked['status'] == 'fail') {
                    return $checked;
                }
                list(, $localPath, $imageFile) = $checked;
                $cfgVal = str_replace($localPath, '', $imageFile);
                break;
            case 'OSS_OPEN_CLOSE':
                $cfgVal = strtolower($cfgVal);
                $sLinkSource = ROOT_DIR . 'upload/image/sp_' . $this->shopId;
                $sLinkDesc = ROOT_DIR . 'public/sp_' . $this->shopId;
                if ($cfgVal == 'open') {
                    shell_exec("rm -rf {$sLinkDesc}");
                } else if (!file_exists($sLinkDesc)) {
                    shell_exec("ln -s {$sLinkSource} {$sLinkDesc}");
                }
                break;
            case 'TIMESTAMP':
                $cfgVal = '?' . trim($cfgVal, '?');
                break;
        }

        $update = $cfgBiz->updateConfigByKey($this->shopId, $cfgKey, [
            'config_value' => $cfgVal,
            'updated_at' => time(),
            'updated_by' => $this->operator
        ]);
        if ($update > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
    }
}
