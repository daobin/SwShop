<?php
/**
 * 仓库业务
 * User: dao bin
 * Date: 2021/8/20
 * Time: 13:43
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class WarehouseBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function saveWarehouse(int $shopId, string $origCode, array $warehouse): int
    {
        if ($shopId <= 0 || empty($warehouse['warehouse_code'])) {
            return 0;
        }

        $time = time();
        $code = strtoupper($warehouse['warehouse_code']);
        $data = [
            'shop_id' => $shopId,
            'warehouse_code' => $code,
            'warehouse_name' => $warehouse['warehouse_name'],
            'sort' => $warehouse['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $warehouse['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $warehouse['operator'] ?? ''
        ];

        if ($this->getWarehouseByCode($shopId, $origCode)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('warehouse')
                ->where(['shop_id' => $shopId, 'warehouse_code' => $origCode])->update($data);
        }

        return $this->dbHelper->table('warehouse')->insert($data);
    }

    public function delWarehouseByCode(int $shopId, string $code): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('warehouse')
            ->where(['shop_id' => $shopId, 'warehouse_code' => $code])->delete();
    }

    public function getWarehouseList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['warehouse_id', 'warehouse_code', 'warehouse_name', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('warehouse')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
    }

    public function getWarehouseByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['warehouse_id', 'warehouse_code', 'warehouse_name', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('warehouse')->where(['shop_id' => $shopId, 'warehouse_code' => $code])
            ->fields($fields)->find();
    }

    public function getDefaultWarehouse(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['warehouse_id', 'warehouse_code', 'warehouse_name', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('warehouse')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->limit(0, 1)->find();
    }

    public function getSysWarehouses(): array
    {
        $warehouses = $this->dbHelper->table('sys_warehouse')->fields(['warehouse_code', 'warehouse_name'])
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($warehouses)) {
            return [];
        }

        return array_column($warehouses, 'warehouse_name', 'warehouse_code');
    }

    public function getSysWarehouseByCode(string $code): array
    {
        return $this->dbHelper->table('sys_warehouse')->where(['warehouse_code' => $code])
            ->fields(['warehouse_code', 'warehouse_name'])->find();
    }
}
