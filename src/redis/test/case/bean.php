<?php
return [
    'config'     => [
        'path' => __DIR__ . '/../config',
    ],
    'redis'      => [
        'class'    => \Swoft\Redis\RedisDb::class,
        'database' => 6,
        'option'   => [
            'prefix' => 'swoft:'
        ]
    ],
    'redis.pool' => [
        'class'   => \Swoft\Redis\Pool::class,
        'redisDb' => \bean('redis')
    ]
];