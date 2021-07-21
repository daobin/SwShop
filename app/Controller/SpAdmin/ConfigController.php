<?php
/**
 * 店铺配置
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\DbHelper;
use App\Helper\LanguageHelper;

class ConfigController extends Controller
{
    public function index()
    {
        if ($this->request->ajax) {
            $grp = $this->request->get['cfg_grp'] ?? 'web_info';
            $cfgList = DbHelper::connection()->table('config')->where(
                ['shop_id' => $this->request->shop_id, 'config_group' => $grp])->select();

            return [
                'code' => 0,
                'data' => $cfgList
            ];
        }

        return $this->render();
    }

    public function edit(){
        if(strtoupper($this->request->getMethod()) == 'POST'){

        }

        $key = $this->request->get['cfg_key'] ?? '';
        if(empty($key)){
            return LanguageHelper::get('invalid_request');
        }

        $cfgInfo = DbHelper::connection()->table('config')->where(
            ['shop_id' => $this->request->shop_id, 'config_key' => $key])->find();
        if(empty($cfgInfo)){
            return LanguageHelper::get('invalid_request');
        }

        return LanguageHelper::get('invalid_request');
    }
}
