<?php
/**
 * 路由助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 16:12
 */
declare(strict_types=1);

namespace App\Helper;

class RouteHelper
{
    const URI_SUFFIX = '.html';

    public static $routeList;

    public static function get(string $requestUri, string $modCtrlAct = 'Module.Controller.Action', array $extra = [])
    {
        $requestUri = trim(strtolower($requestUri), self::URI_SUFFIX);
        $requestUri = trim($requestUri, '/');
        $modCtrlAct = explode('.', $modCtrlAct);
        $mcaCount = count($modCtrlAct);
        if ($mcaCount < 3) {
            return;
        }

        $action = array_pop($modCtrlAct);
        $controller = array_pop($modCtrlAct);
        $module = implode('\\', $modCtrlAct);

        self::$routeList['get'][$requestUri] = [$module, $controller, $action, $extra];
    }

    public static function post(string $requestUri, string $modCtrlAct = 'Module.Controller.Action', array $extra = [])
    {
        $requestUri = trim(strtolower($requestUri), self::URI_SUFFIX);
        $requestUri = trim($requestUri, '/');
        $modCtrlAct = explode('.', $modCtrlAct);
        $mcaCount = count($modCtrlAct);
        if ($mcaCount < 3) {
            return;
        }

        $action = array_pop($modCtrlAct);
        $controller = array_pop($modCtrlAct);
        $module = implode('\\', $modCtrlAct);

        self::$routeList['post'][$requestUri] = [$module, $controller, $action, $extra];
    }

    public static function any(string $requestUri, string $modCtrlAct = 'Module.Controller.Action', array $extra = [])
    {
        self::get($requestUri, $modCtrlAct, $extra);
        self::post($requestUri, $modCtrlAct, $extra);
    }

    public static function buildRoute(&$request)
    {
        $requestUri = preg_replace('/[^\w\d\.\-\/]+/', '', strtolower($request->server['request_uri']));
        $requestUri = trim($requestUri, self::URI_SUFFIX);
        $requestUri = trim($requestUri, '/');
        $requestMethod = strtolower($request->server['request_method']);
        foreach (['get', 'post'] as $method) {
            if ($method != $requestMethod || !isset(self::$routeList[$method])) {
                continue;
            }

            if (isset(self::$routeList[$method][$requestUri])) {
                return self::$routeList[$method][$requestUri];
            }

            foreach (self::$routeList[$method] as $rule => $route) {
                $ruleOrigin = $rule;
                if (isset($route[3]) && $route[3]) {
                    foreach ($route[3] as $key => $value) {
                        $rule = str_replace('<' . $key . '>', '(' . $value . ')', $rule);
                    }
                }

                // 匹配路由
                if ($rule && preg_match("/^{$rule}$/", $requestUri, $uriMatches)) {
                    $getParams = [];
                    // 获取路由参数
                    if (preg_match_all('/<([^<>]+)>/', $ruleOrigin, $ruleMatches)) {
                        array_shift($ruleMatches);
                        foreach ($ruleMatches as $params) {
                            foreach ($params as $idx => $param) {
                                $getParams[$param] ??= $uriMatches[$idx + 1];
                            }
                        }
                    }
                    $request->get = empty($request->get) ? $getParams : array_merge($request->get, $getParams);
                    return $route;
                }
            }
        }

        return ['Index', 'Index', 'pageNotFound'];
    }

    public static function buildUrl(string $modCtrlAct, array $params = [])
    {
        $url = '/';
        $rule = array_search(self::$routeList, $modCtrlAct);
        if (!empty($rule) && $rule != '/' && !empty($params)) {
            foreach ($params as $param => $value) {
                if (strpos($rule, '<' . $param . '>') !== false) {
                    $rule = str_replace('<' . $param . '>', $value, $rule);
                    unset($params[$param]);
                }
            }
            $url = $rule . self::URI_SUFFIX;
        }

        if (empty($params)) {
            return $url;
        }

        $params = http_build_query($params);
        return $url . '?' . $params;
    }
}
