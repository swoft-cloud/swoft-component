<?php

return [
    'config'           => [
        'path' => __DIR__ . '/../config',
    ],
    'redis'            => [
        'class'         => \Swoft\Redis\RedisDb::class,
        'host'          => '127.0.0.1',
        'port'          => 6379,
        'database'      => 0,
        'retryInterval' => 10,
        'readTimeout'   => 0,
        'timeout'       => 2,
        'option'        => [
            'prefix'     => 'swoft-t',
            'serializer' => Redis::SERIALIZER_PHP
        ],
    ],
    'redis-clusters'   => [
        'class'    => \Swoft\Redis\RedisDb::class,
        'option'   => [
            'timeout'    => 3,
            'persistent' => true,
        ],
        'clusters' => [
            [
                'host'         => '127.0.0.1',
                'prefix'       => 'swoft-t',
                'read_timeout' => 1,
                'port'         => 6379,
                'database'     => 1,
                'password'     => 123445,
            ],
        ]
    ],
    'redis.clusters-1' => [
        'class'       => \Swoft\Redis\Pool::class,
        'redisDb'     => \bean('redis-clusters'),
        'minActive'   => 100,
        'maxActive'   => 200,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ],
    'redis.pool'       => [
        'class'   => \Swoft\Redis\Pool::class,
        'redisDb' => \bean('redis'),
        'minActive'   => 100,
        'maxActive'   => 300,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ]
];
