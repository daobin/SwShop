<?php
/**
 * 币种业务
 * User: dao bin
 * Date: 2021/8/20
 * Time: 13:43
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class CurrencyBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function saveWarehouse(int $shopId, string $origCode, array $currency): int
    {
        if ($shopId <= 0 || empty($currency['currency_code'])) {
            return 0;
        }

        $time = time();
        $code = strtoupper($currency['currency_code']);
        $data = [
            'shop_id' => $shopId,
            'currency_code' => $code,
            'currency_name' => $currency['currency_name'],
            'symbol_left' => $currency['symbol_left'],
            'symbol_right' => $currency['symbol_right'],
            'decimal_point' => $currency['decimal_point'],
            'thousands_point' => $currency['thousands_point'],
            'value' => $currency['value'],
            'decimal_places' => $currency['decimal_places'],
            'icon_path' => $currency['icon_path'],
            'sort' => $currency['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $currency['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $currency['operator'] ?? ''
        ];

        if ($this->getCurrencyByCode($shopId, $origCode)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('currency')
                ->where(['shop_id' => $shopId, 'currency_code' => $origCode])->update($data);
        }

        return $this->dbHelper->table('currency')->insert($data);
    }

    public function delCurrencyByCode(int $shopId, string $code): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('currency')
            ->where(['shop_id' => $shopId, 'currency_code' => $code])->delete();
    }

    public function getCurrencyList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = [
            'currency_id', 'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point',
            'thousands_point', 'value', 'decimal_places', 'icon_path', 'sort', 'updated_at', 'updated_by'
        ];
        return $this->dbHelper->table('currency')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
    }

    public function getCurrencyByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = [
            'currency_id', 'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point',
            'thousands_point', 'value', 'decimal_places', 'icon_path', 'sort', 'updated_at', 'updated_by'
        ];

        return $this->dbHelper->table('currency')->where(['shop_id' => $shopId, 'currency_code' => $code])
            ->fields($fields)->find();
    }

    public function getDefaultCurrency(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = [
            'currency_id', 'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point',
            'thousands_point', 'value', 'decimal_places', 'icon_path', 'sort', 'updated_at', 'updated_by'
        ];
        return $this->dbHelper->table('currency')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->limit(0, 1)->find();
    }

    public function getSysCurrencyList(): array
    {
        $fields = [
            'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point',
            'thousands_point', 'value', 'decimal_places', 'icon_path'
        ];

        $currencyList = $this->dbHelper->table('sys_currency')->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($currencyList)) {
            return [];
        }

        return array_column($currencyList, null, 'currency_code');
    }

    public function getSysCurrencyByCode(string $code): array
    {
        $fields = [
            'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point',
            'thousands_point', 'value', 'decimal_places', 'icon_path'
        ];

        return $this->dbHelper->table('sys_currency')->where(['currency_code' => $code])
            ->fields($fields)->find();
    }
}
