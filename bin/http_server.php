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

// 启动 HTTP 服务
new \App\Server\HttpServer();
