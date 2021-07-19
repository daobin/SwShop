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
use App\Helper\DbHelper;
use App\Helper\LanguageHelper;
use App\Helper\RouteHelper;
use App\Helper\SafeHelper;

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
        $charset = ConfigHelper::get('app.charset', 'UTF-8');

        // 判断是否为异步请求
        $request->ajax = isset($request->header['x-requested-with']) && strtoupper($request->header['x-requested-with']) == 'XMLHTTPREQUEST';

        try {
            if (empty($request->header['host'])) {
                throw new \Exception(LanguageHelper::get('invalid_request'));
            }

            $host = trim($request->header['host']);
            $domain = explode('.', $host);
            $domainArrCnt = count($domain);
            if ($domainArrCnt < 2) {
                throw new \Exception(LanguageHelper::get('invalid_request'));
            }

            // 店铺网站合法性验证
            $domain = $domain[$domainArrCnt - 2] . '.' . $domain[$domainArrCnt - 1];
            $shopInfo = DbHelper::connection()->table('sys_shop')
                ->fields(['shop_id', 'shop_status', 'shop_domain', 'shop_domain2', 'shop_domain2_redirect_code'])
                ->whereOr(['shop_domain' => $domain, 'shop_domain2' => $domain])
                ->orderBy(['shop_id' => 'desc'])
                ->find();
            if (empty($shopInfo) || (int)$shopInfo['shop_status'] !== 1) {
                throw new \Exception(LanguageHelper::get('invalid_website'));
            }

            // 店铺多域名情况的跳转处理
            $redirectStatus = (int)$shopInfo['shop_domain2_redirect_code'];
            if ($domain == $shopInfo['shop_domain2'] && in_array($redirectStatus, [301, 302], true) && !empty($shopInfo['shop_domain'])) {
                $response->redirect('http://' . $shopInfo['shop_domain'], $redirectStatus);
                return;
            }

            // 初始化店铺管理配置
            ConfigHelper::initConfigFromDb($shopInfo['shop_id']);
            $request->shop_id = $shopInfo['shop_id'];
            $request->domain = $domain;

            // POST 提交数据的 CSRF 安全防护（基于 Redis）
            $reqMethod = trim($request->server['request_method']);
            $reqMethod = strtoupper($reqMethod);
            if ($reqMethod == 'POST') {
                $safeHelper = new SafeHelper($request, $response);
                if (!isset($request->post['hash_tk']) || !($safeHelper->chkCsrfToken($request->post['hash_tk']))) {
                    if ($request->ajax) {
                        $response->header('Content-type', 'application/json; charset=' . $charset);
                        $response->end(json_encode(['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')]));
                    } else {
                        $response->header('Content-type', 'text/html; charset=' . $charset);
                        $response->end(LanguageHelper::get('invalid_request'));
                    }

                    return;
                }
            }

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
        } catch (\Throwable $e) {
            print_r($e->getMessage() . PHP_EOL);
            print_r($e->getFile() . ' >> ' . $e->getLine() . PHP_EOL);

            if ($request->ajax) {
                $response->header('Content-type', 'application/json; charset=' . $charset);
                $response->end(json_encode(['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')]));
            } else {
                $response->header('Content-type', 'text/html; charset=' . $charset);
                $response->end(LanguageHelper::get('invalid_request'));
            }
        }
    }
}
