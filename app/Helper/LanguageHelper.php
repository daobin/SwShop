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

    public static function get($key, $lang = '')
    {
        if (empty($lang) || !in_array($lang, array_keys(self::$langMaps))) {
            $lang = ConfigHelper::get('app.languages', ['en']);
            $lang = reset($lang);
        }

        $lang = strtolower($lang);
        if (isset(self::$langMaps[$lang][$key])) {
            return self::$langMaps[$lang][$key];
        }

        if ($lang === 'en') {
            return $key;
        }

        $lang = 'en';
        if (isset(self::$langMaps[$lang][$key])) {
            return self::$langMaps[$lang][$key];
        }

        return $key;
    }
}