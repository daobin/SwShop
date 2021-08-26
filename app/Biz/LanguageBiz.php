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

class LanguageBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function saveLanguage(int $shopId, string $origCode, array $language): int
    {
        if ($shopId <= 0 || empty($language['language_code'])) {
            return 0;
        }

        $time = time();
        $code = strtolower($language['language_code']);
        $data = [
            'shop_id' => $shopId,
            'language_code' => $code,
            'language_name' => $language['language_name'],
            'sort' => $language['sort'] ?? 0,
            'created_at' => $time,
            'created_by' => $language['operator'] ?? '',
            'updated_at' => $time,
            'updated_by' => $language['operator'] ?? ''
        ];

        if ($this->getLanguageByCode($shopId, $origCode)) {
            unset($data['created_at'], $data['created_by']);

            return $this->dbHelper->table('language')
                ->where(['shop_id' => $shopId, 'language_code' => $origCode])->update($data);
        }

        return $this->dbHelper->table('language')->insert($data);
    }

    public function delLanguageByCode(int $shopId, string $code): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('language')
            ->where(['shop_id' => $shopId, 'language_code' => $code])->delete();
    }

    public function getLanguageList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['language_id', 'language_name', 'language_code', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('language')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
    }

    public function getLangCodes(int $shopId): array
    {
        $langCodes = $this->getLanguageList($shopId);
        return $langCodes ? array_column($langCodes, 'language_code') : ['en'];
    }

    public function getLanguageByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['language_id', 'language_name', 'language_code', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('language')->where(['shop_id' => $shopId, 'language_code' => $code])
            ->fields($fields)->find();
    }

    public function getDefaultLanguage(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['language_id', 'language_name', 'language_code', 'sort', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('language')->where(['shop_id' => $shopId])->fields($fields)
            ->orderBy(['sort' => 'asc'])->limit(0, 1)->find();
    }

    public function getSysLanguages(): array
    {
        $languages = $this->dbHelper->table('sys_language')->fields(['language_code', 'language_name'])
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($languages)) {
            return [];
        }

        return array_column($languages, 'language_name', 'language_code');
    }

    public function getSysLanguageByCode(string $code): array
    {
        return $this->dbHelper->table('sys_language')->where(['language_code' => $code])
            ->fields(['language_code', 'language_name'])->find();
    }
}
