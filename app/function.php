<?php
/**
 * 公用函数
 * User: dao bin
 * Date: 2021/7/19
 * Time: 16:48
 */
declare(strict_types=1);

function xss_text(string $text, bool $isLangCode = false): string
{
    if ($isLangCode === true) {
        $text = \App\Helper\LanguageHelper::get($text);
    }

    return htmlspecialchars($text);
}
