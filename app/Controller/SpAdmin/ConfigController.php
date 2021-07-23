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
use App\Helper\ConfigHelper;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class ConfigController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            $grp = $this->request->get['cfg_grp'] ?? 'web_info';
            $cfgList = (new ConfigBiz())->getConfigListByGroup($this->request->shop_id, $grp);

            if (!empty($cfgList)) {
                foreach ($cfgList as &$cfgInfo) {
                    if (strtolower($cfgInfo['value_type']) == 'password') {
                        $cfgInfo['config_value'] = hide_chars($cfgInfo['config_value']);
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
        $cfgKey = $this->request->get['cfg_key'] ?? '';
        $cfgKey = strtoupper($cfgKey);
        $configBiz = new ConfigBiz();

        if ($this->request->isPost) {
            if (empty($cfgKey)) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
            }

            $cfgVal = trim($this->request->post['config_value'] ?? '');
            $valType = trim($this->request->post['value_type'] ?? '');
            if (strtolower($valType) == 'password') {
                $cfgVal = SafeHelper::encodeString($cfgVal);
            }

            $update = $configBiz->updateConfigByKey($this->request->shop_id, $cfgKey, [
                'config_value' => $cfgVal,
                'updated_at' => time(),
                'updated_by' => $this->spAdminInfo['account'] ?? '--'
            ]);
            print_r($update);
            if ($update > 0) {
                return ['status' => 'success'];
            }

            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        if (empty($cfgKey)) {
            return LanguageHelper::get('invalid_request');
        }

        $cfgInfo = $configBiz->getConfigByKey($this->request->shop_id, $cfgKey);
        if (empty($cfgInfo)) {
            return LanguageHelper::get('invalid_request');
        }

        $safeHelper = new SafeHelper($this->request, $this->response);
        $cfgInfo['csrf_token'] = $safeHelper->buildCsrfToken('BG', $cfgKey);
        return $this->render($cfgInfo);
    }
}
