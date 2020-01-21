<?php declare(strict_types=1);

return [
    'config'            => [
        'path' => __DIR__ . '/config',
    ],
    /** @see \Swoft\Tcp\Server\TcpDispatcher */
    'tcpDispatcher'     => [
        'middlewares' => [
            'global1test' => \SwoftTest\Tcp\Server\Testing\Middleware\Global1Middleware::class
        ]
    ],
    /** @see \Swoft\Tcp\Protocol */
    'tcpServerProtocol' => [
        // 'type' => \Swoft\Tcp\Packer\JsonPacker::TYPE,
        'type' => \Swoft\Tcp\Packer\SimpleTokenPacker::TYPE,
        // 'openLengthCheck' => true,
    ],
];
