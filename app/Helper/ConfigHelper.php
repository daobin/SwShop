<?php
/**
 * 配置文件助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 11:49
 */
declare(strict_types=1);

namespace App\Helper;

class ConfigHelper
{
    private static $initConfigStatus;
    private static $configMaps;

    public static function initConfig()
    {
        if (self::$initConfigStatus) {
            return;
        }

        $configFiles = glob(ROOT_DIR . 'config/*.php', GLOB_ERR);
        if (empty($configFiles)) {
            return;
        }

        foreach ($configFiles as $configFile) {
            if (!is_file($configFile)) {
                continue;
            }

            $configName = str_replace('.php', '', basename($configFile));
            $configName = strtolower($configName);
            self::$configMaps[$configName] = include $configFile;
        }

        self::$initConfigStatus = true;
    }

    public static function get($key, $default = null)
    {
        $key = trim($key, '.');
        if (empty($key)) {
            return $default;
        }

        $value = [];
        $keys = explode('.', $key);
        foreach ($keys as $key) {
            if (empty($value) && (isset(self::$configMaps[$key]) || isset(self::$configMaps['sw_shop'][$key]))) {
                $value = self::$configMaps[$key];
                continue;
            }
            if (isset($value[$key])) {
                $value = $value[$key];
                continue;
            }

            if (!empty($value)) {
                $value = $default;
            }
            break;
        }

        return $value;
    }

    public static function getAll()
    {
        return self::$configMaps;
    }

    public static function set($key, $value)
    {
        self::$configMaps['sw_shop'][$key] = $value;
    }

    public static function initConfigFromDb($shopId)
    {
        self::initConfig();
        $groups = self::get('app.init_db_config', []);
        if (empty($groups)){
            return;
        }

        foreach($groups as $group){
            $configRows = DbHelper::connection()->table('config')
                ->where(['shop_id' => (int)$shopId, 'config_group' => $group])
                ->select();

            self::$configMaps[$group] = self::formatConfigFromDb($configRows);
        }
    }

    private static function formatConfigFromDb($configRows): array
    {
        if (empty($configRows)) {
            return [];
        }

        $configList = [];
        foreach ($configRows as $configRow) {
            $key = strtolower($configRow['config_key']);
            $configList[$key] = trim($configRow['config_value']);
            switch (strtolower($configRow['value_type'])) {
                case 'int':
                    $configList[$key] = (int)$configList[$configRow['config_key']];
                    break;
                case 'password':
                    break;
                case 'radio':
                    break;
            }
        }

        return $configList;
    }
}
