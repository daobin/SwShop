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
                    switch(strtolower($cfgInfo['value_type'])){
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
            return LanguageHelper::get('invalid_request');
        }

        $cfgInfo = (new ConfigBiz())->getConfigByKey($this->request->shop_id, $cfgKey);
        if (empty($cfgInfo)) {
            return LanguageHelper::get('invalid_request');
        }

        $cfgInfo['csrf_token'] = (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', $cfgKey);
        return $this->render($cfgInfo);
    }

    private function save()
    {
        $cfgKey = $this->request->get['cfg_key'] ?? '';
        $cfgKey = strtoupper($cfgKey);
        if (empty($cfgKey)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $cfgVal = trim($this->request->post['config_value'] ?? '');
        $valType = trim($this->request->post['value_type'] ?? '');
        switch(strtolower($valType)){
            case 'password':
                $cfgVal = SafeHelper::encodeString($cfgVal);
                break;
            case 'int':
                $cfgVal = (int)$cfgVal;
                break;
            case 'list':
                $resetVal = [];
                $cfgVal = trim($cfgVal, ',');
                if(!empty($cfgVal)){
                    $cfgVal = explode(',', $cfgVal);
                    foreach($cfgVal as $val){
                        $val = trim($val);
                        if(empty($val)){
                            continue;
                        }

                        $val = explode('=', $val, 2);
                        if(count($val) == 2){
                            $resetVal[$val[0]] = $val[1];
                        }else{
                            $resetVal[$val[0]] = $val[0];
                        }
                    }
                }
                $cfgVal = json_encode($resetVal);
                break;
        }

        $update = (new ConfigBiz())->updateConfigByKey($this->request->shop_id, $cfgKey, [
            'config_value' => $cfgVal,
            'updated_at' => time(),
            'updated_by' => $this->spAdminInfo['account'] ?? '--'
        ]);
        if ($update > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
    }
}
