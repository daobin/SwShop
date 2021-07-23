<?php
/**
 * 后台配置相关业务逻辑
 * User: dao bin
 * Date: 2021/7/22
 * Time: 16:22
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ConfigBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getConfigListByGroup($shopId, $group)
    {
        return $this->dbHelper->table('config')->where(
            ['shop_id' => $shopId, 'config_group' => $group])->select();
    }

    public function getConfigByKey($shopId, $key)
    {
        return $this->dbHelper->table('config')->where(
            ['shop_id' => $shopId, 'config_key' => $key])->find();
    }

    public function updateConfigByKey($shopId, $key, $data){

    }
}
