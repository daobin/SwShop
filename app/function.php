<?php
/**
 * 公用函数
 * User: dao bin
 * Date: 2021/7/19
 * Time: 16:48
 */
declare(strict_types=1);

// 获取世界时间
function get_world_times(): array
{
    $defaultTime = date_default_timezone_get();
    date_default_timezone_set('Asia/Shanghai');
    $cnTime = date('Y-m-d H:i');
    date_default_timezone_set('America/New_York');
    $usTime = date('Y-m-d H:i');
    date_default_timezone_set('Europe/London');
    $ukTime = date('Y-m-d H:i');
    date_default_timezone_set($defaultTime);

    return [$cnTime, $usTime, $ukTime];
}

function get_timezones(): array
{
    return [
        'Asia/Shanghai' => '中国北京',
        'America/New_York' => '美国纽约',
        'Europe/London' => '英国伦敦',
    ];
}

// 生成指定开头字符的随机字符串
function build_fixed_pre_random(string $fixedPre = 'HD'): string
{
    $time = time();
    $randomString = strtoupper(base64_encode($time . uniqid()));
    $randomString = substr($randomString, mt_rand(0, strlen($randomString) - 8), 4);

    return $fixedPre . date('YmdHi') . $randomString;
}

// URL 字串处理
function process_url_string(string $url): string
{
    $url = trim($url);
    $url = str_replace('_', '-', $url);
    $url = preg_replace('/[^\w\d\-]+/', '-', $url);
    $url = preg_replace('/[\-]+/', '-', $url);

    return strtolower($url);
}

// 格式化价格
function format_price(float $price, array $currency, int $qty = 1, bool $returnSymbol = false): string
{
    if (empty($currency['currency_code'])) {
        return '0';
    }

    $symbolLeft = $currency['symbol_left'] ?? '';
    $symbolRight = $currency['symbol_right'] ?? '';
    $value = $currency['value'] ? (float)$currency['value'] : 0;
    $decimalPlaces = $currency['decimal_places'] ? (int)$currency['decimal_places'] : 0;
    $decimalPoint = $currency['decimal_point'] ?? '.';
    $thousandsPoint = $currency['thousands_point'] ?? ',';

    $price = round(($price * $value), $decimalPlaces) * $qty;
    if ($returnSymbol === true) {
        return $symbolLeft . number_format($price, $decimalPlaces, $decimalPoint, $thousandsPoint) . $symbolRight;
    }

    return number_format($price, $decimalPlaces, $decimalPoint, '');
}

// 格式化价格总额
function format_price_total(float $price, array $currency): string
{
    if (empty($currency['currency_code'])) {
        return '0';
    }

    $symbolLeft = $currency['symbol_left'] ?? '';
    $symbolRight = $currency['symbol_right'] ?? '';
    $decimalPlaces = $currency['decimal_places'] ? (int)$currency['decimal_places'] : 0;
    $decimalPoint = $currency['decimal_point'] ?? '.';
    $thousandsPoint = $currency['thousands_point'] ?? ',';

    return $symbolLeft . number_format($price, $decimalPlaces, $decimalPoint, $thousandsPoint) . $symbolRight;
}

// XSS 防护
function xss_text(string $text, string $langCode = ''): string
{
    $text = trim($text);
    if (!empty($langCode)) {
        $text = \App\Helper\LanguageHelper::get($text, $langCode);
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

// 获取订单状态值
function get_order_status_id(string $status): int
{
    $statusIds = [
        'waiting' => 1,
        'pending' => 2,
        'in_process' => 3,
        'shipped' => 4,
        'canceled' => 5
    ];

    return $statusIds[$status] ?? 0;
}

// 获取订单状态提示
function get_order_status_notes(string $langCode): array
{
    return [
        1 => \App\Helper\LanguageHelper::get('order_waiting_note', $langCode),
        2 => \App\Helper\LanguageHelper::get('order_pending_note', $langCode),
        3 => \App\Helper\LanguageHelper::get('order_in_process_note', $langCode),
        4 => '',
        5 => \App\Helper\LanguageHelper::get('order_canceled_note', $langCode)
    ];
}

function get_order_status_note(int $statusId, string $langCode): string
{
    $statusNotes = get_order_status_notes($langCode);

    return $statusNotes[$statusId] ?? '';
}

// 获取PP支付响应值
function get_paypal_response_val($response, $field)
{
    if (isset($response[$field])) {
        return $response[$field];
    }

    if (isset($response['purchase_units'])) {
        $response = reset($response['purchase_units']);
        if (stripos($field, 'payment_') !== false) {
            $field = substr($field, 8);
            if (isset($response['payments']['authorizations'])) {
                $response = reset($response['payments']['authorizations']);
                return $response[$field] ?? '';
            }
            if (isset($response['payments']['captures'])) {
                $response = reset($response['payments']['captures']);
                return $response[$field] ?? '';
            }
        }
    }

    return '';
}

function add_log($type, $message)
{
    $type = trim($type, '/');
    $path = ROOT_DIR . 'runtime/log/' . $type . '/';
    if (!is_dir($path)) {
        if (!mkdir($path, 0755, true)) {
            return;
        }
    }

    $logfile = $path . date('Ymd');
    file_put_contents($logfile, print_r($message, true) . PHP_EOL);
}
