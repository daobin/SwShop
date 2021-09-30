<?php
/**
 * 客户相关业务处理
 * User: AT0103
 * Date: 2021/8/17
 * Time: 14:54
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;
use App\Helper\LanguageHelper;

class CustomerBiz
{
    private $dbHelper;
    private $langCode;
    public $count;

    public function __construct($langCode)
    {
        $this->dbHelper = new DbHelper();
        $this->langCode = $langCode;
        $this->count = 0;
    }

    public function updateBaseInfo(array $customerData): array
    {
        $shopId = $customerData['shop_id'] ?? 0;
        $customerId = $customerData['customer_id'] ?? 0;
        $customerInfo = $this->getCustomerById($shopId, $customerId);
        if (empty($customerInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_customer', $this->langCode)];
        }
    }

    public function updateEmail(int $shopId, int $customerId, string $email): int
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 0;
        }

        return $this->dbHelper->table('customer')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->update(
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'updated_at' => time(),
                'updated_by' => $operator
            ]);
    }

    public function updateName(int $shopId, int $customerId, string $firstName, string $lastName, string $operator): int
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($firstName) || empty($lastName)) {
            return 0;
        }

        return $this->dbHelper->table('customer')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->update(
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'updated_at' => time(),
                'updated_by' => $operator
            ]);
    }

    public function updatePassword(int $shopId, int $customerId, string $password, string $operator): int
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($password)) {
            return 0;
        }

        return $this->dbHelper->table('customer')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->update(
            [
                'password' => $password,
                'updated_at' => time(),
                'updated_by' => $operator
            ]);
    }

    public function register(array $data): array
    {
        if (empty($data['shop_id'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $email = $data['email'] ?? '';
        $email = substr($email, 0, 100);
        $password = $data['password'] ?? '';
        $password2 = $data['password2'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'fail', 'msg' => 'Invalid email'];
        }
        if (empty($password) || $password !== $password2) {
            return ['status' => 'fail', 'msg' => 'Invalid password'];
        }

        $shopId = (int)$data['shop_id'];
        if (!empty($this->getCustomerByEmail($shopId, $email))) {
            return ['status' => 'fail', 'msg' => 'Email has been registered'];
        }

        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        unset($data['password2']);

        $customerId = $this->dbHelper->table('customer')->insert($data);
        if ($customerId <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        return ['status' => 'success', 'msg' => '', 'customer_info' => $this->getCustomerByEmail($shopId, $email)];
    }

    public function setShippingAddress(int $shopId, int $customerId, int $addrId): int
    {
        if ($shopId <= 0 || $customerId <= 0 || $addrId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('customer')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->update(['shipping_address_id' => $addrId]);
    }

    public function getCustomerList(array $condition, array $orderBy = [], int $page = 1, int $pageSize = 10): array
    {
        if (empty($condition['shop_id'])) {
            return [];
        }

        $shopId = (int)$condition['shop_id'];

        $where = ['shop_id' => $shopId];
        $whereOr = [];
        foreach ($condition as $key => $value) {
            switch ($key) {
                case 'email':
                case 'device_from':
                case 'customer_type':
                    $where[$key] = $value;
                    break;
                case 'customer_name_like':
                    $whereOr['first_name'] = ['like', '%' . $value . '%'];
                    $whereOr['last_name'] = ['like', '%' . $value . '%'];
                    break;
                case 'start_register_at':
                    $where['registered_at'] = ['>=', (int)$value];
                    break;
                case 'end_register_at':
                    $where['registered_at'] = ['<=', (int)$value];
                    break;
            }
        }

        if (empty($orderBy)) {
            $orderBy = ['customer_id' => 'desc'];
        }

        $this->count = $this->dbHelper->table('customer')->where($where)->whereOr($whereOr)->count();
        if ($this->count <= 0) {
            return [];
        }

        $pageTotal = (int)ceil($this->count / $pageSize);
        $page = $page > $pageTotal ? $pageTotal : $page;

        $fields = [
            'customer_id', 'email', 'password', 'first_name', 'last_name', 'shipping_address_id', 'billing_address_id',
            'ip_number', 'ip_country_iso_code_2', 'logined_failure_count', 'registered_at', 'logined_at',
            'updated_at', 'updated_by', 'customer_type', 'device_from'
        ];

        return $this->dbHelper->table('customer')->where($where)->whereOr($whereOr)->fields($fields)
            ->orderBy($orderBy)->page($page, $pageSize)->select();
    }

    public function getCustomerById(int $shopId, int $customerId): array
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return [];
        }

        $fields = [
            'customer_id', 'email', 'password', 'first_name', 'last_name', 'shipping_address_id', 'billing_address_id',
            'ip_number', 'ip_country_iso_code_2', 'logined_failure_count', 'registered_at', 'logined_at',
            'updated_at', 'updated_by', 'customer_type', 'device_from'
        ];
        return $this->dbHelper->table('customer')->where(['shop_id' => $shopId, 'customer_id' => $customerId])->fields($fields)->find();
    }

    public function getCustomerByEmail(int $shopId, string $email): array
    {
        if ($shopId <= 0 || empty($email)) {
            return [];
        }

        $fields = [
            'customer_id', 'email', 'password', 'first_name', 'last_name', 'shipping_address_id', 'billing_address_id',
            'ip_number', 'ip_country_iso_code_2', 'logined_failure_count', 'registered_at', 'logined_at',
            'updated_at', 'updated_by', 'customer_type', 'device_from'
        ];
        return $this->dbHelper->table('customer')->where(['shop_id' => $shopId, 'email' => $email])->fields($fields)->find();
    }
}
