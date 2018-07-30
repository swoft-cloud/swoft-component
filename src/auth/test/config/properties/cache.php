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
        'name' => 'redis1',
        'uri'         => [
            '127.0.0.1:1111',
            '127.0.0.1:1111',
        ],
        'maxIdel'     => 1,
        'maxActive'   => 1,
        'maxWait'     => 1,
        'timeout'     => 1,
        'balancer'    => 'random1',
        'useProvider' => true,
        'provider'    => 'consul1',
    ],
];
