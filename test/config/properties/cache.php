<?php
return [
    'redis'     => [
        'name'        => env('REDIS_NAME', 'redis'),
        'uri'         => explode(',', env('REDIS_URI', '127.0.0.1:6379,127.0.0.1:6379')),
        'maxIdel'     => env('REDIS_MAX_IDEL', 2),
        'maxActive'   => env('REDIS_MAX_ACTIVE', 2),
        'maxWait'     => env('REDIS_MAX_WAIT', 2),
        'timeout'     => env('REDIS_TIMEOUT', 2),
        'maxWaitTime' => env('REDIS_MAX_WAIT_TIME', 3),
        'maxIdleTime' => env('REDIS_MAX_IDLE_TIME', 60),
        'db'          => env('REDIS_DB', 2),
        'prefix'      => env('REDIS_PREFIX', 'redis_'),
        'serialize'   => env('REDIS_SERIALIZE', 1),
        'balancer'    => env('REDIS_BALANCER', 'random'),
        'useProvider' => env('REDIS_USE_PROVIDER', false),
        'provider'    => env('REDIS_PROVIDER', 'consul'),
    ],
    'demoRedis' => [
        'db'     => 2,
        'prefix' => 'demo_redis_',
    ],
];
