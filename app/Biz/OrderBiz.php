<?php
/**
 * 用户订单业务
 * User: dao bin
 * Date: 2021/8/31
 * Time: 9:46
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class OrderBiz
{
    private $dbHelper;
    private $orderFields;
    public $count;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
        $this->count = 0;
        $this->orderFields = [
            'order_id', 'order_number', 'customer_id', 'customer_email', 'customer_name', 'order_status_id', 'order_type',
            'shipping_method', 'shipping_code', 'payment_method', 'payment_code', 'currency_code', 'currency_value',
            'order_total', 'default_currency_total', 'default_currency_code', 'created_at', 'pp_token',
            'ip_number', 'ip_country_iso_code_2', 'host_from', 'device_from'
        ];
    }

    public function createOrder(int $shopId, array $orderSummary, string $operator = ''): bool
    {
        if (
            $shopId <= 0 || empty($orderSummary['prod_list']) || empty($orderSummary['totals'])
            || empty($orderSummary['customer_info']['customer_id'])
            || empty($orderSummary['address_info']['street_address'])
            || empty($orderSummary['shipping_info']['method_code'])
            || empty($orderSummary['payment_info']['method_code'])
            || empty($orderSummary['currency_info']['currency_code'])
            || empty($orderSummary['default_currency_info']['currency_code'])
        ) {
            return false;
        }

        $this->dbHelper->beginTransaction();
        try {
            $time = time();

            $orderNumber = strtoupper(base64_encode($time . uniqid()));
            $orderNumber = substr($orderNumber, mt_rand(0, strlen($orderNumber) - 8), 4);

            $orderId = $this->dbHelper->table('order')->insert([
                'shop_id' => $shopId,
                'order_number' => 'HD' . date('YmdHi') . $orderNumber,
                'customer_id' => $orderSummary['customer_info']['customer_id'],
                'customer_email' => $orderSummary['customer_info']['email'],
                'customer_name' => trim($orderSummary['customer_info']['first_name'] . ' ' . $orderSummary['customer_info']['last_name']),
                'order_status_id' => $orderSummary['order_status_id'],
                'shipping_method' => $orderSummary['shipping_info']['method_name'],
                'shipping_code' => $orderSummary['shipping_info']['method_code'],
                'payment_method' => $orderSummary['payment_info']['method_name'],
                'payment_code' => $orderSummary['payment_info']['method_code'],
                'currency_code' => $orderSummary['currency_info']['currency_code'],
                'currency_value' => $orderSummary['currency_info']['value'],
                'order_total' => format_price($orderSummary['totals']['total']['price'], $orderSummary['currency_info']),
                'default_currency_total' => $orderSummary['totals']['total']['price'],
                'default_currency_code' => $orderSummary['default_currency_info']['currency_code'],
                'ip_number' => $orderSummary['ip'] ?? 0,
                'ip_country_iso_code_2' => $orderSummary['ip_country_iso_code_2'] ?? '',
                'host_from' => $orderSummary['host_from'] ?? '',
                'device_from' => $orderSummary['device_from'] ?? '',
                'order_type' => 'normal',
                'is_guest' => 0,
                'pp_token' => $orderSummary['pp_token'] ?? '',
                'created_at' => $time,
                'created_by' => $operator,
                'updated_at' => $time,
                'updated_by' => $operator
            ]);

            $this->dbHelper->table('order_address')->insert([
                'shop_id' => $shopId,
                'order_id' => $orderId,
                'address_type' => 'shipping',
                'first_name' => $orderSummary['address_info']['first_name'],
                'last_name' => $orderSummary['address_info']['last_name'],
                'street_address' => $orderSummary['address_info']['street_address'],
                'street_address_sub' => $orderSummary['address_info']['street_address_sub'],
                'postcode' => $orderSummary['address_info']['postcode'],
                'city' => $orderSummary['address_info']['city'],
                'zone_id' => $orderSummary['address_info']['zone_id'],
                'zone_name' => $orderSummary['address_info']['zone_name'],
                'country_id' => $orderSummary['address_info']['country_id'],
                'country_name' => $orderSummary['address_info']['country_name'],
                'telephone' => $orderSummary['address_info']['telephone'],
                'created_at' => $time,
                'created_by' => $operator
            ]);

            foreach ($orderSummary['prod_list'] as $sku => $prodInfo) {
                $this->dbHelper->table('order_product')->insert([
                    'shop_id' => $shopId,
                    'order_id' => $orderId,
                    'product_id' => $prodInfo['product_id'],
                    'product_name' => $prodInfo['product_name'],
                    'sku' => $sku,
                    'qty' => $prodInfo['qty'],
                    'price' => format_price($prodInfo['price'], $orderSummary['currency_info']),
                    'default_currency_price' => $prodInfo['price'],
                    'created_at' => $time
                ]);
            }

            $this->dbHelper->table('order_status_history')->insert([
                'shop_id' => $shopId,
                'order_id' => $orderId,
                'order_status_id' => $orderSummary['order_status_id'],
                'is_show' => 1,
                'comment' => $orderSummary['status_comment'],
                'created_at' => $time,
                'created_by' => $operator
            ]);

            foreach ($orderSummary['totals'] as $class => $total) {
                $this->dbHelper->table('order_total')->insert([
                    'shop_id' => $shopId,
                    'order_id' => $orderId,
                    'ot_class' => $class,
                    'ot_title' => $total['title'],
                    'ot_text' => $total['text'],
                    'price' => format_price($total['price'], $orderSummary['currency_info']),
                    'default_currency_price' => $total['price'],
                    'created_at' => $time
                ]);
            }

            unset($orderSummary);
            $this->dbHelper->commit();
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->dbHelper->rollBack();
            return false;
        }

        return true;
    }

    public function updateOrderStatusById(int $shopId, int $orderId, int $orderStatusId, string $comment, bool $isShow = true, string $operator = ''): bool
    {
        $comment = trim($comment);
        if ($shopId <= 0 || $orderId <= 0 || $orderStatusId <= 0 || $comment === '') {
            return false;
        }

        $this->dbHelper->beginTransaction();

        try {
            $this->dbHelper->table('order')->where(['shop_id' => $shopId, 'order_id' => $orderId])
                ->update([
                    'order_status_id' => $orderStatusId,
                    'updated_at' => time(),
                    'updated_by' => trim($operator)
                ]);

            $this->dbHelper->table('order_status_history')->insert([
                'shop_id' => $shopId,
                'order_id' => $orderId,
                'order_status_id' => $orderStatusId,
                'is_show' => (int)$isShow,
                'comment' => $comment,
                'created_at' => time(),
                'created_by' => trim($operator)
            ]);

            $this->dbHelper->commit();
        } catch (\Throwable $e) {
            $this->dbHelper->rollBack();
            return false;
        }

        return true;
    }

    public function getOrderByPpToken(int $shopId, string $token): array
    {
        if ($shopId <= 0 || empty($token)) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'pp_token' => $token];
        return $this->dbHelper->table('order')->where($where)->fields($this->orderFields)->find();
    }

    public function getOrderForTracking(int $shopId, string $email, string $orderNumber): array
    {
        if ($shopId <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($orderNumber)) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'customer_email' => $email, 'order_number' => $orderNumber];
        $orderInfo = $this->dbHelper->table('order')->where($where)->fields($this->orderFields)->find();
        if (empty($orderInfo)) {
            return [];
        }

        $fields = ['order_id', 'product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        $orderProductList = $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_id' => $orderInfo['order_id']])->fields($fields)->select();
        if (empty($orderProductList)) {
            return [];
        }

        $orderInfo['prod_list'] = array_column($orderProductList, null, 'sku');
        return $orderInfo;
    }

    public function getNewOrderListByTime(int $shopId, int $start, int $end): array
    {
        if ($shopId <= 0 || $start <= 0 || $end < $start) {
            return [];
        }

        return $this->dbHelper->table('order')->where(
            ['shop_id' => $shopId, 'created_at' => ['between', [$start, $end]]])
            ->fields(['created_at', 'default_currency_total'])->select();
    }

    public function getOrderList(array $condition, array $orderBy = [], int $page = 1, int $pageSize = 10): array
    {
        if (empty($condition['shop_id'])) {
            return [];
        }

        $shopId = (int)$condition['shop_id'];

        $where = ['shop_id' => $shopId];
        foreach ($condition as $key => $value) {
            switch ($key) {
                case 'order_id':
                case 'customer_id':
                case 'order_status_id':
                    $where[$key] = (int)$value;
                    break;
                case 'order_number':
                case 'customer_email':
                case 'device_from':
                case 'order_type':
                case 'payment_code':
                    $where[$key] = $value;
                    break;
                case 'customer_name_like':
                    $where['customer_name'] = ['like', '%' . $value . '%'];
                    break;
                case 'created_at_between':
                    $where['created_at'] = ['between', $value];
                    break;
            }
        }

        if (empty($orderBy)) {
            $orderBy = ['order_id' => 'desc'];
        }

        $this->count = $this->dbHelper->table('order')->where($where)->count();
        if ($this->count <= 0) {
            return [];
        }

        $pageTotal = (int)ceil($this->count / $pageSize);
        $page = $page > $pageTotal ? $pageTotal : $page;

        return $this->dbHelper->table('order')->where($where)->fields($this->orderFields)
            ->orderBy($orderBy)->page($page, $pageSize)->select();
    }

    public function getProductListByOrderIds(int $shopId, array $orderIds): array
    {
        if ($shopId <= 0 || empty($orderIds)) {
            return [];
        }

        $fields = ['order_id', 'product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        return $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_id' => ['in', $orderIds]])->fields($fields)->select();
    }

    public function getOrderByNumber(int $shopId, string $orderNumber): array
    {
        if ($shopId <= 0 || empty($orderNumber)) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'order_number' => $orderNumber];
        $orderInfo = $this->dbHelper->table('order')->where($where)->fields($this->orderFields)->find();
        if (empty($orderInfo)) {
            return [];
        }

        $fields = ['order_id', 'product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        $orderProductList = $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_id' => $orderInfo['order_id']])->fields($fields)->select();
        if (empty($orderProductList)) {
            return [];
        }

        $orderInfo['prod_list'] = array_column($orderProductList, null, 'sku');
        return $orderInfo;
    }

    public function getCustomerOrderByNumber(int $shopId, int $customerId, string $orderNumber): array
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($orderNumber)) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'customer_id' => $customerId, 'order_number' => $orderNumber];
        $orderInfo = $this->dbHelper->table('order')->where($where)->fields($this->orderFields)->find();
        if (empty($orderInfo)) {
            return [];
        }

        $fields = ['order_id', 'product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        $orderProductList = $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_id' => $orderInfo['order_id']])->fields($fields)->select();
        if (empty($orderProductList)) {
            return [];
        }

        $orderInfo['prod_list'] = array_column($orderProductList, null, 'sku');
        return $orderInfo;
    }

    public function getCustomerLastOne(int $shopId, int $customerId): array
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return [];
        }

        $where = ['shop_id' => $shopId, 'customer_id' => $customerId];
        $orderInfo = $this->dbHelper->table('order')->where($where)->fields($this->orderFields)
            ->orderBy(['order_id' => 'desc'])->limit(0, 1)->find();
        if (empty($orderInfo)) {
            return [];
        }

        $fields = ['order_id', 'product_id', 'product_name', 'sku', 'qty', 'price', 'default_currency_price'];
        $orderProductList = $this->dbHelper->table('order_product')->where(
            ['shop_id' => $shopId, 'order_id' => $orderInfo['order_id']])->fields($fields)->select();
        if (empty($orderProductList)) {
            return [];
        }

        $orderInfo['prod_list'] = array_column($orderProductList, null, 'sku');
        return $orderInfo;
    }

    public function getAddressByOrderId(int $shopId, int $orderId): array
    {
        if ($shopId <= 0 || $orderId <= 0) {
            return [];
        }

        $fields = [
            'address_type', 'first_name', 'last_name', 'street_address', 'street_address_sub',
            'postcode', 'city', 'zone_id', 'zone_name', 'country_id', 'country_name', 'telephone'
        ];

        return $this->dbHelper->table('order_address')->where(
            ['shop_id' => $shopId, 'order_id' => $orderId])->fields($fields)->find();
    }

    public function getHistoryListByOrderId(int $shopId, int $orderId): array
    {
        if ($shopId <= 0 || $orderId <= 0) {
            return [];
        }

        $fields = ['order_status_id', 'is_show', 'comment', 'created_at', 'created_by'];
        return $this->dbHelper->table('order_status_history')->where(
            ['shop_id' => $shopId, 'order_id' => $orderId])->fields($fields)->select();
    }

    public function getTotalListByOrderId(int $shopId, int $orderId): array
    {
        if ($shopId <= 0 || $orderId <= 0) {
            return [];
        }

        $fields = ['ot_class', 'ot_title', 'ot_text', 'price', 'default_currency_price'];
        $totals = $this->dbHelper->table('order_total')->where(
            ['shop_id' => $shopId, 'order_id' => $orderId])->fields($fields)->select();
        if (empty($totals)) {
            return [];
        }

        return array_column($totals, null, 'ot_class');
    }

    public function getSysOrderStatuses(string $langCode): array
    {
        if (empty($langCode)) {
            return [];
        }

        $statuses = $this->dbHelper->table('sys_order_status')
            ->where(['language_code' => $langCode])->fields(['order_status_id', 'status_name'])
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($statuses)) {
            return [];
        }

        return array_column($statuses, 'status_name', 'order_status_id');
    }
}
