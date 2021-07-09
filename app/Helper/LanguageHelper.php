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

            $langName = str_replace('.php', '', basename($langFile));
            $langName = strtolower($langName);
            self::$langMaps[$langName] = include $langFile;
        }
    }

    public static function get($key){
        $lang = ConfigHelper::get('app.lang');
        $lang = strtolower($lang);
        if(isset(self::$langMaps[$lang][$key])){
            return self::$langMaps[$lang][$key];
        }

        if($lang === 'en'){
            return '';
        }

        $lang = 'en';
        if(isset(self::$langMaps[$lang][$key])){
            return self::$langMaps[$lang][$key];
        }

        return '';
    }
}