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

    public function getAdminByAccount(int $shopId, string $account): array
    {
        return $this->dbHelper->table('admin')->where(
            ['shop_id' => $shopId, 'account' => $account])
            ->find();
    }
}
