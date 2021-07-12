<?php
/**
 * HTTP 服务启动文件
 * User: dao bin
 * Date: 2021/7/7
 * Time: 16:48
 */
declare(strict_types=1);

namespace App\Server;

use App\Helper\ConfigHelper;
use App\Helper\LanguageHelper;

class HttpServer
{
    public function __construct($host = '0.0.0.0', $port = 9501)
    {
        // 加载配置项
        ConfigHelper::initConfig();

        // 加载多语言
        LanguageHelper::initLang();

        // 加载 HTTP 服务
        $server = new \Swoole\Http\Server($host, $port);
        $server->set([
//            'daemonize' => true,
            'open_eof_check' => true,
            'open_eof_split' => true,
            'package_eof' => "\r\n",
            'enable_static_handler' => true,
            'document_root' => ROOT_DIR . 'public',
            'http_index_files' => [
                'index.html'
            ],
        ]);
        $server->on('Request', [HttpServer::class, 'requestHandler']);
        $server->start();
    }

    public static function requestHandler($request, $response)
    {
        $requestUri = trim($request->server['request_uri'], '/');

        // 路由设置
        [$controller, $action] = $requestUri ? explode('/', $requestUri) : ['index', 'index'];
        $controller = empty($controller) ? 'index' : $controller;
        $controller = strtolower($controller);
        $controller = str_replace('_', '', ucwords($controller, '_'));
        $action = empty($action) ? 'index' : $action;
        $action = strtolower($action);

        try {
            $charset = ConfigHelper::get('app.charset', 'UTF-8');

            // 默认加载模块
            $module = ConfigHelper::get('app.default_module', 'Index');
            $module = ucfirst(strtolower($module));

            $request->module = $module;
            $request->controller = $controller;
            $request->action = $action;

            $controller = 'App\\Controller\\' . $module . '\\' . $controller . 'Controller';
            $return = (new $controller($request, $response))->$action();
            if (is_array($return) || is_object($return)) {
                $response->header('Content-type', 'application/json; charset=' . $charset);
                $response->end(json_encode($return));
            } else {
                $response->header('Content-type', 'text/html; charset=' . $charset);
                $response->end($return);
            }
        } catch (\Exception | \Error $e) {
            print_r($e->getMessage() . PHP_EOL);
            print_r($e->getFile() . ' :: ' . $e->getLine() . PHP_EOL);

            $response->end(LanguageHelper::get('hi_friend'));
        }
    }
}
