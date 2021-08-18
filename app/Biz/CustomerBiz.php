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

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function register(array $data): array
    {
        if (empty($data['shop_id'])) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
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
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        return ['status' => 'success', 'msg' => '', 'customer_info' => $this->getCustomerByEmail($shopId, $email)];
    }

    public function getCustomerByEmail(int $shopId, string $email): array
    {
        if ($shopId <= 0 || empty($email)) {
            return [];
        }

        $fields = [
            'customer_id', 'email', 'password', 'first_name', 'last_name', 'shipping_address_id', 'billing_address_id',
            'ip_number', 'logined_failure_count'
        ];
        return $this->dbHelper->table('customer')->where(['shop_id' => $shopId, 'email' => $email])->fields($fields)->find();
    }
}
