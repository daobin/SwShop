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
    private static $configMaps;

    private function __construct()
    {
    }

    public static function initConfig()
    {
        if (self::$configMaps) {
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
    }

    public static function get(string $key, $default = null)
    {
        $key = trim($key, '.');
        if (empty($key)) {
            return $default;
        }

        $value = '';
        $keys = explode('.', $key);
        foreach ($keys as $key) {
            if (empty($value) && isset(self::$configMaps[$key])) {
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
}
