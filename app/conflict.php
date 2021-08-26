<?php
/**
 * 一些兼容或冲突问题的处理
 * User: AT0103
 * Date: 2021/8/20
 * Time: 10:50
 */
declare(strict_types=1);

namespace Oss\OssClient {
    function is_resource($resource)
    {
        if (\Swoole\Runtime::getHookFlags() & SWOOLE_HOOK_CURL) {
            return \is_resource($resource) || $resource instanceof \Swoole\Curl\Handler;
        }

        return \is_resource($resource);
    }
}

namespace Oss\Http {
    function is_resource($resource)
    {
        if (\Swoole\Runtime::getHookFlags() & SWOOLE_HOOK_CURL) {
            return \is_resource($resource) || $resource instanceof \Swoole\Curl\Handler;
        }

        return \is_resource($resource);
    }
}
