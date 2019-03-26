<?php
// The code for this file is to provide some code hints for phpstorm.

namespace PHPSTORM_META {

    // This is a saner and self-documented format for PhpStorm 2016.2 and later
    // Try QuickDoc on these "magic" functions, or even Go to definition!
    use Swoft\Config\Config;
    use Swoft\Console\Input\Input;
    use Swoft\Console\Output\Output;
    use Swoft\Event\Manager\EventManager;
    use Swoft\Http\Server\HttpDispatcher;

    override(\Swoft::getBean(0),
        map([
            'config'         => Config::class,
            'eventManager'   => EventManager::class,
            // http server
            'httpRouter'     => \Swoft\Http\Server\Router\Router::class,
            'httpServer'     => \Swoft\Http\Server\HttpServer::class,
            'httpDispatcher' => HttpDispatcher::class,
            // ws server
            'wsRouter'       => \Swoft\WebSocket\Server\Router\Router::class,
            'wsServer'       => \Swoft\WebSocket\Server\WebSocketServer::class,
            'wsDispatcher'   => \Swoft\WebSocket\Server\WsDispatcher::class,
            // console
            'cliApp'         => \Swoft\Console\Application::class,
            'cliRouter'      => \Swoft\Console\Router\Router::class,
            'input'          => Input::class,
            'output'         => Output::class,
        ])
    );

    // for bean function
    override(\bean(0),
        map([
            'config'         => Config::class,
            'eventManager'   => EventManager::class,
            // http server
            'httpRouter'     => \Swoft\Http\Server\Router\Router::class,
            'httpServer'     => \Swoft\Http\Server\HttpServer::class,
            'httpDispatcher' => HttpDispatcher::class,
            // ws server
            'wsRouter'       => \Swoft\WebSocket\Server\Router\Router::class,
            'wsServer'       => \Swoft\WebSocket\Server\WebSocketServer::class,
            'wsDispatcher'   => \Swoft\WebSocket\Server\WsDispatcher::class,
            // console
            'cliApp'         => \Swoft\Console\Application::class,
            'cliRouter'      => \Swoft\Console\Router\Router::class,
            'input'          => Input::class,
            'output'         => Output::class,
        ])
    );
}
