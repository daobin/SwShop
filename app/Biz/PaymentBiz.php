<?php

/**
 * 支付方式业务
 * User: AT0103
 * Date: 2021/9/15 0015
 * Time: 17:18
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;
use App\Helper\SafeHelper;

class PaymentBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function savePayment(int $shopId, string $origCode, array $payment): int
    {
        if ($shopId <= 0 || empty($payment['method_code'])) {
            return 0;
        }

        $time = time();

        $this->dbHelper->beginTransaction();
        try {
            if (!empty($payment['cfg_list']) && is_array($payment['cfg_list'])) {
                foreach ($payment['cfg_list'] as $key => $val) {
                    if(in_array($key, ['API_CLIENT_ID', 'API_SECRET'])){
                        $val = SafeHelper::encodeString($val);
                    }
                    if(empty($val)){
                        continue;
                    }

                    $this->dbHelper->table('config')->where(['shop_id' => $shopId, 'config_key' => $key])
                        ->update([
                            'config_value' => $val,
                            'updated_at' => $time,
                            'updated_by' => $payment['operator'] ?? ''
                        ]);
                }
            }

            $data = [
                'shop_id' => $shopId,
                'method_code' => strtolower($payment['method_code']),
                'method_name' => $payment['method_name'],
                'sort' => $payment['sort'] ?? 0,
                'created_at' => $time,
                'created_by' => $payment['operator'] ?? '',
                'updated_at' => $time,
                'updated_by' => $payment['operator'] ?? ''
            ];

            if ($this->getPaymentByCode($shopId, $origCode)) {
                unset($data['created_at'], $data['created_by']);

                $this->dbHelper->table('payment_method')
                    ->where(['shop_id' => $shopId, 'method_code' => $origCode])->update($data);
            } else {
                $this->dbHelper->table('payment_method')->insert($data);
            }

            $this->dbHelper->commit();
            return 1;

        } catch (\Throwable $e) {
            $this->dbHelper->rollBack();
        }

        return 0;
    }

    public function delPaymentByCode(int $shopId, string $code): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('payment_method')
            ->where(['shop_id' => $shopId, 'method_code' => $code])->delete();
    }

    public function getPaymentList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['payment_method_id', 'method_code', 'method_name', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('payment_method')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
    }

    public function getPaymentByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['payment_method_id', 'method_code', 'method_name', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('payment_method')->where(['shop_id' => $shopId, 'method_code' => $code])
            ->fields($fields)->find();
    }

    public function getSysPayments(): array
    {
        $payments = $this->dbHelper->table('sys_payment_method')->fields(['method_code', 'method_name'])
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($payments)) {
            return [];
        }

        return array_column($payments, 'method_name', 'method_code');
    }

    public function getSysPaymentByCode(string $code): array
    {
        return $this->dbHelper->table('sys_payment_method')->where(['method_code' => $code])
            ->fields(['method_code', 'method_name'])->find();
    }
}