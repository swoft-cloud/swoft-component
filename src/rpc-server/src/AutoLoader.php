<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Swoole\CloseListener;
use Swoft\Rpc\Server\Swoole\ConnectListener;
use Swoft\Rpc\Server\Swoole\ReceiveListener;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function beans(): array
    {
        return [
            'rpcServer' => [
                'class' => ServiceServer::class,
                'on'    => [
                    SwooleEvent::CONNECT => \bean(ConnectListener::class),
                    SwooleEvent::CLOSE   => \bean(CloseListener::class),
                    SwooleEvent::RECEIVE => \bean(ReceiveListener::class),
                ]
            ],
            'rpcServerPacket' => [
                'class' => Packet::class
            ]
        ];
    }
}