<?php
// The code for this file is to provide some code hints for phpstorm.

namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        \Swoft::getBean('') => [
            'config' instanceof \Swoft\Config\Config,
            'eventManager' instanceof \Swoft\Event\Manager\EventManager,
            // http server
            'httpRouter' instanceof \Swoft\Http\Server\Router\Router,
            'httpDispatcher' instanceof \Swoft\Http\Server\HttpDispatcher,
            // ws server
            'wsRouter' instanceof \Swoft\WebSocket\Server\Router\Router,
            'wsDispatcher' instanceof \Swoft\WebSocket\Server\Dispatcher,
        ],
        // bean function
        \bean('')           => [
            'config' instanceof \Swoft\Config\Config,
            'eventManager' instanceof \Swoft\Event\Manager\EventManager,
            // http server
            'httpRouter' instanceof \Swoft\Http\Server\Router\Router,
            'httpDispatcher' instanceof \Swoft\Http\Server\HttpDispatcher,
            // ws server
            'wsRouter' instanceof \Swoft\WebSocket\Server\Router\Router,
            'wsDispatcher' instanceof \Swoft\WebSocket\Server\Dispatcher,
        ]
    ];
}
