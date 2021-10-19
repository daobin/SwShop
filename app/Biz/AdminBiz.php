<?php
/**
 * 后台管理员相关业务逻辑
 * User: dao bin
 * Date: 2021/7/22
 * Time: 16:22
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class AdminBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function save(array $data): int
    {
        $shopId = $data['shop_id'] ?? 0;
        if ((int)$shopId <= 0) {
            return 0;
        }

        $time = time();
        $save = [
            'account' => $data['account'],
            'updated_at' => $time,
            'updated_by' => $data['operator'] ?? ''
        ];
        if (!empty($data['password'])) {
            $save['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $adminId = $data['admin_id'] ?? 0;
        $adminInfo = $this->getAdminById($shopId, (int)$adminId);
        if (empty($adminInfo)) {
            $save['shop_id'] = $shopId;
            $save['created_at'] = $time;
            $save['created_by'] = $data['operator'] ?? '';
            return $this->dbHelper->table('admin')->insert($save);
        }

        return $this->dbHelper->table('admin')->where(['shop_id' => $shopId, 'admin_id' => $adminId])
            ->update($save);
    }

    public function delById(int $shopId, int $adminId): int
    {
        if ($shopId <= 0 || $adminId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('admin')->where(['shop_id' => $shopId, 'admin_id' => $adminId])->delete();
    }

    public function getAdminList(int $shopId, string $createBy = ''): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        if ($createBy !== '') {
            $whereOr = [
                'account' => $createBy,
                'created_by' => $createBy
            ];
            return $this->dbHelper->table('admin')->where(['shop_id' => $shopId])->whereOr($whereOr)
                ->fields($fields)->select();
        }

        return $this->dbHelper->table('admin')->where(['shop_id' => $shopId])->fields($fields)->select();
    }

    public function getAdminById(int $shopId, int $adminId): array
    {
        if ($shopId <= 0 || $adminId <= 0) {
            return [];
        }

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('admin')->where(['shop_id' => $shopId, 'admin_id' => $adminId])
            ->fields($fields)->find();
    }

    public function getAdminByAccount(int $shopId, string $account): array
    {
        if ($shopId <= 0 || empty($account)) {
            return [];
        }

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('admin')->where(['shop_id' => $shopId, 'account' => $account])
            ->fields($fields)->find();
    }
}
