<?php

use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Server\SwooleEvent;
use Swoft\Session\SwooleStorage;
use Swoft\WebSocket\Server\WebSocketServer;

return [
    'config'              => [
        'path' => dirname(__DIR__) . '/config',
    ],
    'rpcServer'         => [
        'port' => 28307,
        'class' => \Swoft\Rpc\Server\ServiceServer::class,
    ],
    'httpServer' => [
        'port' => 28306,
    ],
    'wsServer'          => [
        'class'   => WebSocketServer::class,
        'port'    => 28308,
        'listener' => [
            // 'rpc' => bean('rpcServer'),
            // 'tcp' => bean('tcpServer'),
        ],
        'on'      => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug'   => 1,
        // 'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
    'wsConnectionManager' => [
        'storage' => bean('wsConnectionStorage')
    ],
    'wsConnectionStorage' => [
        'class' => SwooleStorage::class,
    ],
];
