<?php

use Swoft\Http\Server\Parser\RequestParser;
use Swoft\Http\Server\Router\HandlerMapping;
use Swoft\Http\Server\ServerDispatcher;

return [
    'serverDispatcher' => [
        'class' => ServerDispatcher::class,
    ],
    'httpRouter' => [
        'class' => HandlerMapping::class,
    ],
    'requestParser' => [
        'class' => RequestParser::class,
    ],
];