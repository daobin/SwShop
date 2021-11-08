<?php
/**
 * 用户地址业务
 * User: dao bin
 * Date: 2021/8/31
 * Time: 9:46
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class AddressBiz
{
    private $dbHelper;
    private $sysCountryFields;
    public $count;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
        $this->sysCountryFields = ['country_id', 'country_name', 'iso_code_2', 'iso_code_3', 'sort', 'icon_path', 'is_high_risk', 'updated_at', 'updated_by'];
        $this->count = 0;
    }

    public function saveCountry(int $shopId, int $origCountryId, array $country): int
    {
        if ($shopId <= 0 || empty($country['country_id'])) {
            return 0;
        }

        $time = time();

        $data = [
            'shop_id' => $shopId,
            'country_id' => $country['country_id'],
            'country_name' => $country['country_name'],
            'iso_code_2' => $country['iso_code_2'],
            'iso_code_3' => $country['iso_code_3'],
            'icon_path' => $country['icon_path'],
            'is_high_risk' => $country['is_high_risk'],
            'sort' => $country['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $country['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $country['operator'] ?? ''
        ];

        if ($this->getCountryById($shopId, $origCountryId)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('country')
                ->where(['shop_id' => $shopId, 'country_id' => $origCountryId])->update($data);
        }

        if (empty($country['init_zone'])) {
            return $this->dbHelper->table('country')->insert($data);
        }

        $res = 1;
        $this->dbHelper->beginTransaction();
        try {
            $this->dbHelper->table('country')->insert($data);

            $zoneList = $this->getSysZoneList((int)$country['country_id']);
            if (!empty($zoneList)) {
                foreach ($zoneList as $zone) {
                    $this->dbHelper->table('zone')->insert([
                        'shop_id' => $shopId,
                        'zone_id' => $zone['zone_id'],
                        'zone_name' => $zone['zone_name'],
                        'country_id' => $country['country_id'],
                        'sort' => $zone['sort'],
                        'created_at' => $time,
                        'created_by' => $country['operator'] ?? '',
                        'updated_at' => $time,
                        'updated_by' => $country['operator'] ?? ''
                    ]);
                }
            }


            $this->dbHelper->commit();

        } catch (\Throwable $e) {
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    public function getCountryById(int $shopId, int $countryId): array
    {
        if ($shopId <= 0 || $countryId <= 0) {
            return [];
        }

        $fields = ['country_id', 'country_name', 'iso_code_2', 'iso_code_3', 'sort', 'icon_path', 'is_high_risk', 'updated_at', 'updated_by'];

        return $this->dbHelper->table('country')->where(['shop_id' => $shopId, 'country_id' => $countryId])
            ->fields($fields)->find();
    }

    public function getCountryList(int $shopId, int $page = 1, int $pageSize = 10): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $this->count = $this->dbHelper->table('country')->where(['shop_id' => $shopId])->count();
        if ($this->count <= 0) {
            return [];
        }

        $pageTotal = (int)ceil($this->count / $pageSize);
        $page = $page > $pageTotal ? $pageTotal : $page;

        $fields = ['country_id', 'country_name', 'iso_code_2', 'iso_code_3', 'sort', 'icon_path', 'is_high_risk', 'updated_at', 'updated_by'];

        return $this->dbHelper->table('country')->where(['shop_id' => $shopId])
            ->fields($fields)->orderBy(['sort' => 'asc', 'country_id' => 'asc'])->page($page, $pageSize)->select();
    }

    public function delCountryById(int $shopId, int $countryId): int
    {
        if ($shopId <= 0 || $countryId <= 0) {
            return 0;
        }

        $res = 1;
        $this->dbHelper->beginTransaction();
        try {
            $this->dbHelper->table('zone')
                ->where(['shop_id' => $shopId, 'country_id' => $countryId])->delete();

            $this->dbHelper->table('country')
                ->where(['shop_id' => $shopId, 'country_id' => $countryId])->delete();

            $this->dbHelper->commit();

        } catch (\Throwable $e) {
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    public function saveSysCountry(array $country): int
    {
        if (empty($country['iso_code_2']) || empty($country['iso_code_3']) || empty($country['country_name'])) {
            return 0;
        }

        $time = time();

        $data = [
            'country_id' => $country['country_id'],
            'country_name' => $country['country_name'],
            'iso_code_2' => $country['iso_code_2'],
            'iso_code_3' => $country['iso_code_3'],
            'created_at' => $time,
            'created_by' => $country['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $country['operator'] ?? ''
        ];

        $countryInfo = $this->getSysCountryById($country['country_id']);
        if (!empty($countryInfo)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('sys_country')
                ->where(['country_id' => $countryInfo['country_id']])->update($data);
        }

        return $this->dbHelper->table('sys_country')->insert($data);
    }

    public function getSysCountryList(): array
    {
        $countryList = $this->dbHelper->table('sys_country')->fields($this->sysCountryFields)
            ->orderBy(['sort' => 'asc', 'iso_code_2' => 'asc'])->select();
        if (empty($countryList)) {
            return [];
        }

        return array_column($countryList, null, 'country_id');
    }

    public function getSysCountryById(int $countryId): array
    {
        if ($countryId <= 0) {
            return [];
        }

        return $this->dbHelper->table('sys_country')->where(['country_id' => $countryId])->fields($this->sysCountryFields)->find();
    }

    public function getSysCountryByCode2(string $code): array
    {
        if (strlen($code) != 2) {
            return [];
        }

        return $this->dbHelper->table('sys_country')->where(['iso_code_2' => $code])->fields($this->sysCountryFields)->find();
    }

    public function getSysCountryByCode3(string $code): array
    {
        if (strlen($code) != 3) {
            return [];
        }

        return $this->dbHelper->table('sys_country')->where(['iso_code_3' => $code])->fields($this->sysCountryFields)->find();
    }

    public function getSysCountryByName(string $name): array
    {
        if (empty($name)) {
            return [];
        }

        return $this->dbHelper->table('sys_country')->where(['country_name' => $name])->fields($this->sysCountryFields)->find();
    }

    public function saveZone(int $shopId, int $origZoneId, array $zone): int
    {
        if ($shopId <= 0 || empty($zone['zone_id'])) {
            return 0;
        }

        $time = time();

        $data = [
            'shop_id' => $shopId,
            'country_id' => $zone['country_id'],
            'zone_id' => $zone['zone_id'],
            'zone_name' => $zone['zone_name'],
            'zone_code' => $zone['zone_code'],
            'sort' => $zone['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $zone['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $zone['operator'] ?? ''
        ];

        if ($this->getZoneById($shopId, $origZoneId)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('zone')
                ->where(['shop_id' => $shopId, 'zone_id' => $origZoneId])->update($data);
        }

        return $this->dbHelper->table('zone')->insert($data);
    }

    public function getZoneById(int $shopId, int $zoneId): array
    {
        if ($shopId <= 0 || $zoneId <= 0) {
            return [];
        }

        $fields = ['zone_id', 'zone_name', 'zone_code', 'country_id', 'sort', 'updated_at', 'updated_by'];

        return $this->dbHelper->table('zone')->where(['shop_id' => $shopId, 'zone_id' => $zoneId])
            ->fields($fields)->find();
    }

    public function getZoneList(int $shopId, int $countryId, int $page = 1, int $pageSize = 10): array
    {
        if ($shopId <= 0 || $countryId <= 0) {
            return [];
        }

        $this->count = $this->dbHelper->table('zone')->where(['shop_id' => $shopId, 'country_id' => $countryId])->count();
        if ($this->count <= 0) {
            return [];
        }

        $pageTotal = (int)ceil($this->count / $pageSize);
        $page = $page > $pageTotal ? $pageTotal : $page;

        $fields = ['zone_id', 'zone_name', 'zone_code', 'country_id', 'sort', 'updated_at', 'updated_by'];

        return $this->dbHelper->table('zone')->where(['shop_id' => $shopId, 'country_id' => $countryId])
            ->fields($fields)->orderBy(['sort' => 'asc', 'zone_id' => 'asc'])->page($page, $pageSize)->select();
    }

    public function delZoneById(int $shopId, int $zoneId): int
    {
        if ($shopId <= 0 || $zoneId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('zone')
            ->where(['shop_id' => $shopId, 'zone_id' => $zoneId])->delete();
    }

    public function getSysZoneList(int $countryId): array
    {
        if ($countryId <= 0) {
            return [];
        }

        $fields = ['zone_id', 'zone_name', 'zone_code', 'country_id', 'sort'];
        $currencyList = $this->dbHelper->table('sys_zone')->where(['country_id' => $countryId])
            ->fields($fields)->orderBy(['sort' => 'asc', 'zone_code' => 'asc'])->select();
        if (empty($currencyList)) {
            return [];
        }

        return array_column($currencyList, null, 'zone_id');
    }

    public function getSysZoneById(int $zoneId): array
    {
        if ($zoneId <= 0) {
            return [];
        }

        $fields = ['zone_id', 'zone_name', 'zone_code', 'country_id', 'sort'];
        return $this->dbHelper->table('sys_zone')->where(['zone_id' => $zoneId])->fields($fields)->find();
    }

    public function getSysZoneByCode(string $zoneCode): array
    {
        if (empty($zoneCode)) {
            return [];
        }

        $fields = ['zone_id', 'zone_name', 'zone_code', 'country_id', 'sort'];
        return $this->dbHelper->table('sys_zone')->where(['zone_code' => $zoneCode])->fields($fields)->find();
    }

    public function saveSysZone(array $zone): int
    {
        if (empty($zone['country_id']) || empty($zone['zone_name']) || empty($zone['zone_code'])) {
            return 0;
        }

        $time = time();

        $data = [
            'country_id' => $zone['country_id'],
            'zone_name' => $zone['zone_name'],
            'zone_code' => $zone['zone_code'],
            'sort' => 0,
            'created_at' => $time,
            'created_by' => $zone['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $zone['operator'] ?? ''
        ];

        $zoneInfo = $this->getSysZoneByCode($zone['zone_code']);
        if (!empty($zoneInfo)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('sys_zone')
                ->where(['zone_id' => $zoneInfo['zone_id']])->update($data);
        }

        return $this->dbHelper->table('sys_zone')->insert($data);
    }

    public function getAddressListByCustomerId(int $shopId, int $customerId): array
    {
        if ($shopId <= 0 || $customerId <= 0) {
            return [];
        }

        $fields = [
            'customer_address_id', 'customer_id', 'address_type', 'first_name', 'last_name', 'street_address', 'street_address_sub',
            'postcode', 'city', 'zone_id', 'zone_name', 'country_id', 'country_name', 'telephone', 'updated_at', 'updated_by'
        ];

        return $this->dbHelper->table('customer_address')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId])->fields($fields)->select();
    }

    public function getAddressById(int $shopId, int $customerId, int $addrId): array
    {
        if ($shopId <= 0 || $customerId <= 0 || $addrId <= 0) {
            return [];
        }

        $fields = [
            'customer_address_id', 'customer_id', 'address_type', 'first_name', 'last_name', 'street_address', 'street_address_sub',
            'postcode', 'city', 'zone_id', 'zone_name', 'country_id', 'country_name', 'telephone', 'updated_at', 'updated_by'
        ];

        return $this->dbHelper->table('customer_address')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId, 'customer_address_id' => $addrId])->fields($fields)->find();
    }

    public function deleteAddressById(int $shopId, int $customerId, int $addrId): bool
    {
        if ($shopId <= 0 || $customerId <= 0 || $addrId <= 0) {
            return true;
        }

        $addrInfo = $this->getAddressById($shopId, $customerId, $addrId);
        if (empty($addrInfo)) {
            return true;
        }

        $cnt = $this->dbHelper->table('customer_address')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId, 'customer_address_id' => $addrId])->delete();

        return $cnt > 0 ? true : false;
    }

    public function saveAddress(int $shopId, int $customerId, array $address): int
    {
        if ($shopId <= 0 || $customerId <= 0 || !isset($address['customer_address_id'])) {
            return 0;
        }

        $setDefault = empty($address['set_default']) ? 0 : 1;
        unset($address['set_default']);

        $addrId = (int)$address['customer_address_id'];
        $addrInfo = $this->getAddressById($shopId, $customerId, $addrId);
        $address['shop_id'] = $shopId;
        $address['customer_id'] = $customerId;

        if (empty($addrInfo)) {
            $addrId = $this->dbHelper->table('customer_address')->insert($address);

            $where = ['shop_id' => $shopId, 'customer_id' => $customerId];
            $customerInfo = $this->dbHelper->table('customer')->where($where)->fields(['shipping_address_id'])->find();
            if ($setDefault || empty($customerInfo['shipping_address_id'])) {
                $this->dbHelper->table('customer')->where($where)->update(['shipping_address_id' => $addrId]);
            }

            return $addrId;
        }

        $where = ['shop_id' => $shopId, 'customer_id' => $customerId];
        $customerInfo = $this->dbHelper->table('customer')->where($where)->fields(['shipping_address_id'])->find();
        if ($setDefault || empty($customerInfo['shipping_address_id'])) {
            $this->dbHelper->table('customer')->where($where)->update(['shipping_address_id' => $addrId]);
        }

        unset($address['created_at'], $address['created_by']);
        $this->dbHelper->table('customer_address')->where(
            ['shop_id' => $shopId, 'customer_id' => $customerId, 'customer_address_id' => $addrId])->update($address);

        return $addrId;
    }
}
