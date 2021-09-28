<?php
/**
 * Paypal 支付记录
 * User: dao bin
 * Date: 2021/9/26
 * Time: 17:08
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class PaypalBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function add(array $paypal): int
    {
        if((int)$paypal['shop_id'] <= 0){
            return 0;
        }

        $save = [
            'shop_id' => $paypal['shop_id'],
            'order_id' => $paypal['order_id'] ?? 0,
            'operation' => $paypal['operation'] ?? '',
            'ack' => $paypal['ack'] ?? '',
            'payment_code' => $paypal['payment_code'] ?? '',
            'payment_status' => $paypal['payment_status'] ?? '',
            'payment_date' => $paypal['payment_date'] ?? '2021-01-01 00:00:00',
            'txn_id' => $paypal['txn_id'] ?? '',
            'currency_code' => $paypal['currency_code'] ?? '',
            'amount' => $paypal['amount'] ?? 0,
            'created_at' => time()
        ];

        return $this->dbHelper->table('paypal')->insert($save);
    }

    public function getByTxnId(int $shopId, string $txnId): array
    {
        if ($shopId <= 0 || empty($txnId)) {
            return [];
        }

        $fields = ['order_id', 'operation', 'ack', 'payment_code', 'payment_status', 'payment_date', 'txn_id'];
        return $this->dbHelper->table('paypal')->where(
            ['shop_id' => $shopId, 'txn_id' => $txnId])->fields($fields)->find();
    }
}
