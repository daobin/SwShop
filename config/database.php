<?php
declare(strict_types=1);

return [
    'mysql' => [
        'master' => [
            'host' => '192.168.16.66',
            'port' => 3306,
            'username' => 'xxxxxx',
            'password' => 'xxxxxx',
            'database' => 'xxxxxx',
            'charset' => 'utf8mb4',
            'table_prefix' => 'hd_',
        ],
    ],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 16379,
        'auth' => 'xxxxxx'
    ],
];
