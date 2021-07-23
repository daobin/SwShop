<?php
/**
 * 公用函数
 * User: dao bin
 * Date: 2021/7/19
 * Time: 16:48
 */
declare(strict_types=1);

// XSS 防护
function xss_text(string $text, bool $isLangCode = false): string
{
    $text ??= '';
    if ($isLangCode === true) {
        $text = \App\Helper\LanguageHelper::get($text);
    }

    return htmlspecialchars($text);
}

// 隐藏字串中间部分数据
function hide_chars(string $str): string
{
    $str = trim($str);
    if ($str == '') {
        return $str;
    }

    $length = mb_strlen($str, 'utf-8');
    if ($length <= 3) {
        return implode('', array_fill(0, $length, '*'));
    }

    if ($length <= 6) {
        $start_show_char = $end_show_char = 1;
    } elseif ($length <= 8) {
        $start_show_char = $end_show_char = 2;
    } else {
        $start_show_char = $end_show_char = 3;
    }

    $fillCount = $length - $start_show_char - $end_show_char;
    $fillCount = $fillCount > 12 ? 12 : $fillCount;

    return mb_substr($str, 0, $start_show_char, 'utf-8')
        . implode('', array_fill(0, $fillCount, '*'))
        . mb_substr($str, 0 - $end_show_char, null, 'utf-8');
}

// 验证文件内容是否有安全风险
function chk_file_security_is_risk(string $filename): bool
{
    // 需要检测的文件不存在视为有风险
    if (!file_exists($filename)) {
        return true;
    }

    // 读取长度前 1000 的内容
    $fd = @fopen($filename, 'rb');
    if (!$fd) {
        return true;
    }

    $content = fread($fd, 1000);
    $content = preg_replace('/[\s]+/', '', $content);
    $content = str_replace(['"', '\'', '.'], '', $content);
    fclose($fd);

    if (
        stripos($content, '<?php') !== false
        || stripos($content, 'create_function') !== false
        || stripos($content, 'eval') !== false
        || stripos($content, 'system') !== false
        || stripos($content, 'assert') !== false
        || stripos($content, 'base64_decode') !== false
    ) {
        return true;
    }

    return false;
}
