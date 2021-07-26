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

    public function getConfigListByGroup(int $shopId, string $group): array
    {
        $group = trim($group);
        if ($shopId <= 0 || empty($group)) {
            return [];
        }

        return $this->dbHelper->table('config')->where(
            ['shop_id' => $shopId, 'config_group' => $group])->select();
    }

    public function getConfigByKey(int $shopId, string $key): array
    {
        $key = trim($key);
        if ($shopId <= 0 || empty($key)) {
            return [];
        }

        return $this->dbHelper->table('config')->where(
            ['shop_id' => $shopId, 'config_key' => $key])->find();
    }

    public function updateConfigByKey(int $shopId, string $key, array $data): int
    {
        $key = trim($key);
        if ($shopId <= 0 || empty($key) || empty($data)) {
            return 0;
        }

        return $this->dbHelper->table('config')->where(
            ['shop_id' => $shopId, 'config_key' => $key])->update($data);
    }
}
