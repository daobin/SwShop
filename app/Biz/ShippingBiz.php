<?php

/**
 * 货运方式业务
 * User: AT0103
 * Date: 2021/9/15 0015
 * Time: 17:18
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ShippingBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function saveShipping(int $shopId, string $origCode, array $shipping): int
    {
        if ($shopId <= 0 || empty($shipping['method_code'])) {
            return 0;
        }

        $time = time();

        $data = [
            'shop_id' => $shopId,
            'method_code' => strtolower($shipping['method_code']),
            'method_name' => $shipping['method_name'],
            'note' => $shipping['note'] ?? '',
            'sort' => $shipping['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $shipping['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $shipping['operator'] ?? ''
        ];

        if ($this->getshippingByCode($shopId, $origCode)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('shipping_method')
                ->where(['shop_id' => $shopId, 'method_code' => $origCode])->update($data);
        }

        return $this->dbHelper->table('shipping_method')->insert($data);
    }

    public function delShippingByCode(int $shopId, string $code): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('shipping_method')
            ->where(['shop_id' => $shopId, 'method_code' => $code])->delete();
    }

    public function getShippingList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['shipping_method_id', 'method_code', 'method_name', 'note', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('shipping_method')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
    }

    public function getShippingByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['shipping_method_id', 'method_code', 'method_name', 'note', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('shipping_method')->where(['shop_id' => $shopId, 'method_code' => $code])
            ->fields($fields)->find();
    }

    public function getSysShippings(): array
    {
        $shippings = $this->dbHelper->table('sys_shipping_method')->fields(['method_code', 'method_name'])
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($shippings)) {
            return [];
        }

        return array_column($shippings, 'method_name', 'method_code');
    }

    public function getSysShippingByCode(string $code): array
    {
        return $this->dbHelper->table('sys_shipping_method')->where(['method_code' => $code])
            ->fields(['method_code', 'method_name'])->find();
    }
}