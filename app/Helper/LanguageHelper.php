<?php
/**
 * 国际化多语言助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 11:50
 */
declare(strict_types=1);

namespace App\Helper;

class LanguageHelper
{
    private static $langMaps;

    private function __construct()
    {
    }

    public static function initLang()
    {
        $langFiles = glob(ROOT_DIR . 'i18n_lang/*.php', GLOB_ERR);
        if (empty($langFiles)) {
            return;
        }

        foreach ($langFiles as $langFile) {
            if (!is_file($langFile)) {
                continue;
            }

            $langCode = str_replace('.php', '', basename($langFile));
            $langCode = strtolower($langCode);
            self::$langMaps[$langCode] = include $langFile;
        }
    }

    public static function get($key, $langCode = '')
    {
        $langCode = $langCode ? strtolower($langCode) : 'en';

        if (isset(self::$langMaps[$langCode][$key])) {
            return self::$langMaps[$langCode][$key];
        }

        if ($langCode === 'en') {
            return $key;
        }

        $langCode = 'en';
        if (isset(self::$langMaps[$langCode][$key])) {
            return self::$langMaps[$langCode][$key];
        }

        return $key;
    }
}