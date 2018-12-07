<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use SwoftTest\Auth\Testing\Manager;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Auth\Middleware\AuthMiddleware;

return [
    'serverDispatcher' => [
        'middlewares' => [
            AuthMiddleware::class,
        ]
    ],
    AuthManagerInterface::class => [
        'class' => Manager::class
    ],
];
