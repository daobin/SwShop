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

    public function buildForgotPasswordToken(int $shopId, string $email): string
    {
        $email = trim($email);
        if ($shopId <= 0 || empty($email)) {
            return '';
        }

        $customerInfo = $this->getCustomerByEmail($shopId, $email);
        if (empty($customerInfo)) {
            return '';
        }

        $time = time();
        $token = build_fixed_pre_random();
        $build = $this->dbHelper->table('forgot_password')->insert([
            'shop_id' => $shopId,
            'email' => $email,
            'token' => $token,
            'expired' => $time + 1800,
            'created_at' => $time,
            'updated_at' => $time
        ]);

        return $build > 0 ? $token : '';
    }

    public function getForgotPasswordByToken(int $shopId, string $token): array
    {
        if ($shopId <= 0 || empty($token)) {
            return [];
        }

        return $this->dbHelper->table('forgot_password')->where(['shop_id' => $shopId, 'token' => $token])
            ->fields(['forgot_password_id', 'email', 'expired', 'status'])->find();
    }

    public function updateForgotPasswordStatus(int $shopId, int $forgotId, int $status): int
    {
        if ($shopId <= 0 || $forgotId <= 0 || !in_array($status, [1, 2])) {
            return 0;
        }

        return $this->dbHelper->table('forgot_password')
            ->where(['shop_id' => $shopId, 'forgot_password_id' => $forgotId])
            ->update(['status' => $status, 'updated_at' => time()]);
    }

    public function submitCustomerService(array $csData): int
    {
        $shopId = $csData['shop_id'] ?? 0;
        $shopId = (int)$shopId;
        if ($shopId <= 0) {
            return 0;
        }

        $serviceType = trim($csData['service_type']);
        if ($serviceType != 'pre' && $serviceType != 'after') {
            return 0;
        }

        $time = time();
        return $this->dbHelper->table('customer_service')->insert([
            'shop_id' => $shopId,
            'service_type' => $serviceType,
            'customer_id' => $csData['customer_id'] ?? 0,
            'customer_name' => trim($csData['customer_name']),
            'customer_email' => trim($csData['customer_email']),
            'order_time' => $csData['order_time'] ?? 0,
            'order_number' => $csData['order_number'] ?? '',
            'question' => trim($csData['question']),
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }

    public function updateBaseInfo(array $customerData): array
    {
        $shopId = $customerData['shop_id'] ?? 0;
        $customerId = $customerData['customer_id'] ?? 0;
        $operator = $customerData['operator'] ?? '';

        $shopId = (int)$shopId;
        if ($shopId <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request', $this->langCode)];
        }

        $customerId = (int)$customerId;
        $customerInfo = $this->getCustomerById($shopId, $customerId);
        if (empty($customerInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_customer', $this->langCode)];
        }

        $firstName = $customerData['first_name'] ?? '';
        $lastName = $customerData['last_name'] ?? '';
        if ($this->updateName($shopId, $customerId, $firstName, $lastName, $operator) <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_name', $this->langCode)];
        }

        $email = $customerData['email'] ?? '';
        $emailCustomerInfo = $this->getCustomerByEmail($shopId, $email);
        if ($emailCustomerInfo && $customerId != (int)$emailCustomerInfo['customer_id']) {
            return ['status' => 'fail', 'msg' => 'Email has been registered'];
        }
        if ($this->updateEmail($shopId, $customerId, $email, $operator) <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('email_invalid', $this->langCode)];
        }

        $password = $customerData['password'] ?? '';
        if ($password != '' && $this->updatePassword($shopId, $customerId, $password, $operator) <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('pwd_invalid', $this->langCode)];
        }

        return ['status' => 'success'];
    }

    public function updateEmail(int $shopId, int $customerId, string $email, string $operator): int
    {
        if ($shopId <= 0 || $customerId <= 0 || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 0;
        }

        $this->dbHelper->beginTransaction();

        try {
            $where = ['shop_id' => $shopId, 'customer_id' => $customerId];
            $this->dbHelper->table('customer')->where($where)->update(
                [
                    'email' => $email,
                    'updated_at' => time(),
                    'updated_by' => $operator
                ]);
            if ($this->dbHelper->table('order')->where($where)->count() > 0) {
                $this->dbHelper->table('order')->where($where)->update([
                    'customer_email' => $email,
                    'updated_at' => time(),
                    'updated_by' => $operator
                ]);
            }
            $res = 1;
            $this->dbHelper->commit();
        } catch (\Throwable $e) {
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
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
                'password' => password_hash($password, PASSWORD_DEFAULT),
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
            return ['status' => 'fail', 'msg' => LanguageHelper::get('email_invalid', $this->langCode)];
        }
        if (empty($password) || $password !== $password2) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('pwd_invalid', $this->langCode)];
        }

        $shopId = (int)$data['shop_id'];
        if (!empty($this->getCustomerByEmail($shopId, $email))) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('email_registered', $this->langCode)];
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

    public function getNewCustomerListByTime(int $shopId, int $start, int $end): array
    {
        if ($shopId <= 0 || $start <= 0 || $end < $start) {
            return [];
        }

        return $this->dbHelper->table('customer')->where(
            ['shop_id' => $shopId, 'registered_at' => ['between', [$start, $end]]])
            ->fields(['registered_at'])->select();
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
                case 'register_at_between':
                    $where['registered_at'] = ['between', $value];
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
