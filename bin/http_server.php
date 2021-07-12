<?php
/**
 * HTTP 服务启动文件
 * User: dao bin
 * Date: 2021/7/7
 * Time: 16:48
 */
declare(strict_types=1);

define('ROOT_DIR', realpath(__DIR__ . '/../') . '/');

// 自动加载
require ROOT_DIR . 'vendor/autoload.php';

error_reporting(E_ALL);
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('error_log', ROOT_DIR . 'runtime/log/' . date('Y') . '/err_' . date('Ymd') . '.log');
if(!file_exists(ROOT_DIR . 'runtime/log/' . date('Y'))){
    mkdir(ROOT_DIR . 'runtime/log/' . date('Y'), 0755);
}

// 启动 HTTP 服务
new \App\Server\HttpServer();
