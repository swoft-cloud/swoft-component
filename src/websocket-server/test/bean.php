<?php declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\WebSocket\Server\WsServerBean;
use SwoftTest\WebSocket\Server\Testing\Middleware\GlobalMiddleware;

return [
    'config'                     => [
        'path' => __DIR__ . '/config',
    ],
    WsServerBean::MSG_DISPATCHER => [
        'middlewares' => [
            GlobalMiddleware::class,
        ]
    ]
];
