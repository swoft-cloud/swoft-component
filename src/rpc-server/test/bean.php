<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\Rpc\Server\ServiceDispatcher;
use SwoftTest\Rpc\Server\Testing\Middleware\UserMd;

return [
    'config'            => [
        'path' => __DIR__ . '/config',
    ],
    'serviceDispatcher' => [
        'class'       => ServiceDispatcher::class,
        'middlewares' => [
            UserMd::class
        ]
    ]
];
