<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
return [
    'redis' => [
        'name'        => env('REDIS_NAME', 'redis'),
        'uri'         => explode(',', env('REDIS_URI', '127.0.0.1:6379, 127.0.0.1:6379')),
        'maxIdel'     => env('REDIS_MAX_IDEL', 2),
        'maxActive'   => env('REDIS_MAX_ACTIVE', 2),
        'maxWait'     => env('REDIS_MAX_WAIT', 2),
        'timeout'     => env('REDIS_TIMEOUT', 2),
        'balancer'    => env('REDIS_BALANCER', 'random'),
        'useProvider' => env('REDIS_USE_PROVIDER', false),
        'provider'    => env('REDIS_PROVIDER', 'consul'),
    ],
];
