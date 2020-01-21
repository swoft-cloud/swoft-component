<?php

return [
    'config'              => [
        'path' => __DIR__ . '/config',
    ],
    'redis'               => [
        'class'         => \Swoft\Redis\RedisDb::class,
        'host'          => '127.0.0.1',
        'port'          => 6379,
        'database'      => 0,
        'retryInterval' => 10,
        'readTimeout'   => 0,
        'timeout'       => 2,
        'password'      => '123$~@456',
        'driver'        => 'phpredis',
        'option'        => [
            'prefix' => 'swoft-t_x',
        ],
    ],
    'redis.pool'          => [
        'class'       => \Swoft\Redis\Pool::class,
        'redisDb'     => \bean('redis'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ],
    'redis-clusters'      => [
        'class'    => \Swoft\Redis\RedisDb::class,
        'option'   => [
            'timeout'      => 1.2,
            'persistent'   => false,
            'auth'         => '',
            'read_timeout' => 1.1,
            'prefix'       => 'swoft:',
            'serializer'   => RedisCluster::SERIALIZER_PHP,
        ],
        'clusters' => [
            [
                'host' => '10.0.0.2',
                'port' => 7000,
            ],
            [
                'host'         => '10.0.0.4',
                'port'         => 7002,
                'database'     => 1,
                'prefix'       => 'swoft:s4:',
                'read_timeout' => 1,
                'password'     => ''
            ],
        ]
    ],
    'redis.clusters.pool' => [
        'class'       => \Swoft\Redis\Pool::class,
        'redisDb'     => \bean('redis-clusters'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ],
    // inc redis
    'inc'                 => [
        'class'         => \Swoft\Redis\RedisDb::class,
        'host'          => '127.0.0.1',
        'port'          => 6379,
        'database'      => 0,
        'retryInterval' => 10,
        'readTimeout'   => 0,
        'timeout'       => 2,
        'option'        => [
            'prefix' => 'swoft-t',
        ],
    ],
    'redis.inc.pool'      => [
        'class'       => \Swoft\Redis\Pool::class,
        'redisDb'     => \bean('inc'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ]
];
