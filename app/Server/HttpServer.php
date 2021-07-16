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
use App\Helper\RouteHelper;

class HttpServer
{
    public function __construct($host = '0.0.0.0', $port = 9501)
    {
        // 加载配置项
        ConfigHelper::initConfig();
        // 加载多语言
        LanguageHelper::initLang();
        // 设置一键协程化 Hook 的函数范围
        \Co::set(['hook_flags' => SWOOLE_HOOK_ALL]);

        // 设置 HTTP 服务
        $server = new \Swoole\Http\Server($host, $port);
        $server->set([
//            'daemonize' => true,
            'open_length_check' => true,
            'worker_num' => swoole_cpu_num() * 2,
            'enable_coroutine' => true,
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
        try {
            $charset = ConfigHelper::get('app.charset', 'UTF-8');

            // 路由设置
            list($module, $controller, $action) = RouteHelper::buildRoute($request);
            $request->module = $module;
            $request->controller = $controller;
            $request->action = $action;

            // 调用路由资源
            $controller = 'App\\Controller\\' . $module . '\\' . $controller . 'Controller';
            $return = (new $controller($request, $response))->$action();
            if (is_array($return) || is_object($return)) {
                $response->header('Content-type', 'application/json; charset=' . $charset);
                $response->end(json_encode($return));
            } else {
                $response->header('Content-type', 'text/html; charset=' . $charset);
                $response->end($return);
            }
        } catch (\ErrorException | \Exception | \Error $e) {
            print_r($e->getMessage() . PHP_EOL);
            print_r($e->getFile() . ' >> ' . $e->getLine() . PHP_EOL);

            $response->end(LanguageHelper::get('hi_friend'));
        }
    }
}
