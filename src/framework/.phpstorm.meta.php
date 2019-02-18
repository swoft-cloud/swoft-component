<?php
// The code for this file is to provide some code hints for phpstorm.

namespace PHPSTORM_META {

    // This is a saner and self-documented format for PhpStorm 2016.2 and later
    // Try QuickDoc on these "magic" functions, or even Go to definition!
    use Swoft\Console\Output\Output;

    override(\Swoft::getBean(0),
        map([
            'config'         => \Swoft\Config\Config::class,
            'eventManager'   => \Swoft\Event\Manager\EventManager::class,
            // http server
            'httpRouter'     => \Swoft\Http\Server\Router\Router::class,
            'httpDispatcher' => \Swoft\Http\Server\HttpDispatcher::class,
            // ws server
            'wsRouter'       => \Swoft\WebSocket\Server\Router\Router::class,
            'wsDispatcher'   => \Swoft\WebSocket\Server\Dispatcher::class,
            // console
            'input'          => \Swoft\Console\Input\Input::class,
            'output'         => Output::class,
            Output::class    => Output::class,
        ])
    );

    // for bean function
    override(\bean(0),
        map([
            'config'         => \Swoft\Config\Config::class,
            'eventManager'   => \Swoft\Event\Manager\EventManager::class,
            // http server
            'httpRouter'     => \Swoft\Http\Server\Router\Router::class,
            'httpDispatcher' => \Swoft\Http\Server\HttpDispatcher::class,
            // ws server
            'wsRouter'       => \Swoft\WebSocket\Server\Router\Router::class,
            'wsDispatcher'   => \Swoft\WebSocket\Server\Dispatcher::class,
            // console
            'input'          => \Swoft\Console\Input\Input::class,
            'output'         => Output::class,
            Output::class    => Output::class,
        ])
    );
}
