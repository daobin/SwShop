<?php
/**
 * 店铺业务
 * User: dao bin
 * Date: 2021/8/26
 * Time: 10:15
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ShopBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getShopByDomain(string $domain): array
    {
        if (empty($domain)) {
            return [];
        }

        return $this->dbHelper->table('sys_shop')
            ->fields(['shop_id', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code'])
            ->whereOr(['shop_domain' => $domain, 'shop_domain2' => $domain])
            ->orderBy(['shop_id' => 'desc'])->find();
    }
}
