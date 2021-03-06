<?php
/**
 * 店铺业务
 * User: dao bin
 * Date: 2021/8/26
 * Time: 10:15
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ShopBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function saveShop(array $data): int
    {
        if (empty($data['shop_name']) || empty($data['shop_domain'])) {
            return 0;
        }

        $time = time();
        $save = [
            'shop_name' => $data['shop_name'],
            'shop_domain' => $data['shop_domain'],
            'shop_domain2' => $data['shop_domain2'] ?? '',
            'shop_domain2_redirect_code' => $data['redirect_code'] ?? 0,
            'shop_status' => $data['shop_status'] ?? 0,
            'updated_at' => $time,
            'updated_by' => $data['operator'] ?? ''
        ];

        $shopId = $data['shop_id'] ?? 0;
        $shopInfo = $this->getShopById((int)$shopId);
        if (!empty($shopInfo)) {
            return $this->dbHelper->table('sys_shop')->where(['shop_id' => $shopId])->update($save);
        }

        $res = 0;
        $save['created_at'] = $time;
        $save['created_by'] = $data['operator'] ?? '';
        $this->dbHelper->beginTransaction();
        try {
            $shopId = $this->dbHelper->table('sys_shop')->insert($save);
            $this->initShopData($shopId);

            $res = 1;
            $this->dbHelper->commit();
        } catch (\Throwable $e) {
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    private function initShopData(int $shopId)
    {
        if ($shopId <= 0) {
            throw new \Exception('Shop Added Failure.');
            return;
        }

        $time = time();
        $initData = [
            'config' => [
                'fields' => ['shop_id', 'config_group', 'config_key', 'config_value', 'value_type', 'config_name', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, 'web_info', 'WEBSITE_NAME', '', '', '站点名称', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'WEBSITE_LOGO', '', 'image', '站点 LOGO', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'TIMEZONE', 'America/New_York', '', '系统时区', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'TIMESTAMP', '?' . $time, '', '静态资源时间戳', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'TKD_TITLE', '', '', '站点 Meta Title（SEO 优化）', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'TKD_KEYWORDS', '', '', '站点 Meta Keywords（SEO 优化）', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'TKD_DESCRIPTION', '', '', '站点 Meta Description（SEO 优化）', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'WEIGHT_UNIT', '', 'list', '重量单位', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'SIZE_UNIT', '', 'list', '尺寸单位', $time, 'init', $time, 'init'],
                    [$shopId, 'web_info', 'INDEX_BOTTOM_TEXT', '', '', '首页底部文案', $time, 'init', $time, 'init'],
                    [$shopId, 'redis', 'REDIS_HOST', '', '', 'Redis 地址', $time, 'init', $time, 'init'],
                    [$shopId, 'redis', 'REDIS_PORT', '', 'int', 'Redis 端口', $time, 'init', $time, 'init'],
                    [$shopId, 'redis', 'REDIS_AUTH', '', 'password', 'Redis 认证', $time, 'init', $time, 'init'],
                    [$shopId, 'redis', 'REDIS_EXPIRE', '1800', 'int', 'Redis 过期时间（秒）', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'OSS_ACCESS_KEY_ID', '', 'password', 'OSS 口令', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'OSS_ACCESS_KEY_SECRET', '', 'password', 'OSS 密钥', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'OSS_ENDPOINT', '', '', 'OSS 终端', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'OSS_BUCKET', '', '', 'OSS Bucket', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'OSS_OPEN_CLOSE', 'close', 'radio', 'OSS 开启关闭', $time, 'init', $time, 'init'],
                    [$shopId, 'oss', 'FILE_HOST', '', '', '文件服务地址', $time, 'init', $time, 'init'],
                    [$shopId, 'mail', 'SMTP_HOST', '', '', 'SMTP 服务地址', $time, 'init', $time, 'init'],
                    [$shopId, 'mail', 'SMTP_PORT', '', '', 'SMTP 服务端口', $time, 'init', $time, 'init'],
                    [$shopId, 'mail', 'SMTP_USERNAME', '', '', 'SMTP 服务发送邮箱', $time, 'init', $time, 'init'],
                    [$shopId, 'mail', 'SMTP_PASSWORD', '', 'password', 'SMTP 服务发送密码', $time, 'init', $time, 'init'],
                    [$shopId, 'mail', 'CUSTOMER_SERVICE_EMAIL', '', '', '客服邮箱', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal', 'PAYPAL_CHECKOUT_URL', '', '', '支付页链接', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal', 'PAYPAL_API_URL', '', '', '支付 API 链接', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal', 'PAYPAL_API_CLIENT_ID', '', 'password', 'API 客户端 ID', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal', 'PAYPAL_API_SECRET', '', 'password', 'API 密钥', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal_cc', 'PAYPAL_CC_CHECKOUT_URL', '', '', '支付页链接', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal_cc', 'PAYPAL_CC_API_URL', '', '', '支付 API 链接', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal_cc', 'PAYPAL_CC_API_CLIENT_ID', '', 'password', 'API 客户端 ID', $time, 'init', $time, 'init'],
                    [$shopId, 'paypal_cc', 'PAYPAL_CC_API_SECRET', '', 'password', 'API 密钥', $time, 'init', $time, 'init'],
                ]
            ],
            'admin' => [
                'fields' => ['shop_id', 'account', 'password', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, 'admin' . date('Y'), password_hash(date('Ymd.H'), PASSWORD_DEFAULT), $time, 'init', $time, 'init']
                ]
            ],
            'banner' => [
                'fields' => ['shop_id', 'title', 'code', 'banner_status', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, '首页主轮播图', 'index_main_loop', 0, $time, 'init', $time, 'init']
                ]
            ],
            'email_tpl' => [
                'fields' => ['shop_id', 'subject', 'template', 'banner_images', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, 'Welcome', 'welcome', '', $time, 'init', $time, 'init'],
                    [$shopId, 'Forgot the password', 'forgot_password', '', $time, 'init', $time, 'init'],
                    [$shopId, 'Password reset successfully', 'password_success', '', $time, 'init', $time, 'init'],
                    [$shopId, 'Customer question', 'customer_service', '', $time, 'init', $time, 'init'],
                    [$shopId, 'Successfully ordered', 'order_success', '', $time, 'init', $time, 'init']
                ]
            ],
        ];

        $sysFirstLanguage = $this->dbHelper->table('sys_language')->fields(['language_name', 'language_code'])
            ->orderBy(['sort' => 'asc'])->find();
        if (!empty($sysFirstLanguage)) {
            $initData['language'] = [
                'fields' => ['shop_id', 'language_name', 'language_code', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, $sysFirstLanguage['language_name'], $sysFirstLanguage['language_code'], $time, 'init', $time, 'init']
                ]
            ];
        }

        $sysFirstCurrency = $this->dbHelper->table('sys_currency')->fields(
            ['currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point', 'thousands_point', 'value', 'decimal_places', 'icon_path'])
            ->orderBy(['sort' => 'asc'])->find();
        if (!empty($sysFirstCurrency)) {
            $initData['currency'] = [
                'fields' => [
                    'shop_id', 'currency_name', 'currency_code', 'symbol_left', 'symbol_right', 'decimal_point', 'thousands_point', 'value',
                    'decimal_places', 'icon_path', 'created_at', 'created_by', 'updated_at', 'updated_by'
                ],
                'list' => [
                    [
                        $shopId, $sysFirstCurrency['currency_name'], $sysFirstCurrency['currency_code'],
                        $sysFirstCurrency['symbol_left'], $sysFirstCurrency['symbol_right'],
                        $sysFirstCurrency['decimal_point'], $sysFirstCurrency['thousands_point'],
                        $sysFirstCurrency['value'], $sysFirstCurrency['decimal_places'], $sysFirstCurrency['icon_path'],
                        $time, 'init', $time, 'init'
                    ]
                ]
            ];
        }

        $sysFirstShipping = $this->dbHelper->table('sys_shipping_method')->where(['method_status' => 1])
            ->fields(['method_name', 'method_code'])->orderBy(['sort' => 'asc'])->find();
        if (!empty($sysFirstShipping)) {
            $initData['shipping_method'] = [
                'fields' => ['shop_id', 'method_name', 'method_code', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'list' => [
                    [$shopId, $sysFirstShipping['method_name'], $sysFirstShipping['method_code'], $time, 'init', $time, 'init']
                ]
            ];
        }

        foreach ($initData as $table => $data) {
            if ($this->dbHelper->table($table)->where(['shop_id' => $shopId])->count() > 0) {
                continue;
            }

            $fields = $data['fields'];
            foreach ($data['list'] as $item) {
                $insertData = [];
                foreach ($item as $idx => $val) {
                    $insertData[$fields[$idx]] = $val;
                }

                $this->dbHelper->table($table)->insert($insertData);
            }
        }
    }

    public function getShopList(): array
    {
        $fields = [
            'shop_id', 'shop_name', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code',
            'created_at', 'updated_at', 'updated_by'
        ];
        return $this->dbHelper->table('sys_shop')->fields($fields)
            ->orderBy(['shop_id' => 'desc'])->select();
    }

    public function getShopById(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = [
            'shop_id', 'shop_name', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code',
            'created_at', 'updated_at', 'updated_by'
        ];
        return $this->dbHelper->table('sys_shop')->fields($fields)->where(['shop_id' => $shopId])->find();
    }

    public function getShopByDomain(string $domain): array
    {
        if (empty($domain)) {
            return [];
        }

        $fields = [
            'shop_id', 'shop_name', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code',
            'created_at', 'updated_at', 'updated_by'
        ];
        return $this->dbHelper->table('sys_shop')->fields($fields)
            ->whereOr(['shop_domain' => $domain, 'shop_domain2' => $domain])
            ->orderBy(['shop_id' => 'desc'])->find();
    }

    public function delShopById(int $shopId): int
    {
        if ($shopId <= 0) {
            return 0;
        }

        return $this->dbHelper->table('sys_shop')->where(['shop_id' => $shopId])->delete();
    }
}
