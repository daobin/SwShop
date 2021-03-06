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
        if ((int)$shopId <= 0 || empty($data['account'])) {
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

    public function getAdminList(int $shopId, string $operator): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $firstAdmin = $this->dbHelper->table('admin')->where(['shop_id' => $shopId])->fields(['account'])
            ->orderBy(['admin_id' => 'asc'])->find();

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        if (!empty($firstAdmin['account']) && $operator != $firstAdmin['account']) {
            $whereOr = [
                'account' => $operator,
                'created_by' => $operator
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

    public function saveSysAdmin(array $data): int
    {
        if (empty($data['account'])) {
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
        $adminInfo = $this->getSysAdminById((int)$adminId);
        if (empty($adminInfo)) {
            $save['created_at'] = $time;
            $save['created_by'] = $data['operator'] ?? '';
            return $this->dbHelper->table('sys_admin')->insert($save);
        }

        return $this->dbHelper->table('sys_admin')->where(['admin_id' => $adminId])->update($save);
    }

    public function delSysAdminById(int $adminId): int
    {
        if ($adminId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('sys_admin')->where(['admin_id' => $adminId])->delete();
    }

    public function getSysAdminList(string $operator): array
    {
        $firstAdmin = $this->dbHelper->table('sys_admin')->fields(['account'])->orderBy(['admin_id' => 'asc'])->find();

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        if (!empty($firstAdmin['account']) && $operator != $firstAdmin['account']) {
            $whereOr = [
                'account' => $operator,
                'created_by' => $operator
            ];
            return $this->dbHelper->table('sys_admin')->whereOr($whereOr)->fields($fields)->select();
        }

        return $this->dbHelper->table('sys_admin')->fields($fields)->select();
    }

    public function getSysAdminById(int $adminId): array
    {
        if ($adminId <= 0) {
            return [];
        }

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('sys_admin')->where(['admin_id' => $adminId])->fields($fields)->find();
    }

    public function getSysAdminByAccount(string $account): array
    {
        if (empty($account)) {
            return [];
        }

        $fields = ['admin_id', 'account', 'password', 'created_at', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('sys_admin')->where(['account' => $account])
            ->fields($fields)->find();
    }

}
