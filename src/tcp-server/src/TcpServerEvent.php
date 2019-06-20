<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

/**
 * Class TcpServerEvent
 *
 * @since 2.0
 */
final class TcpServerEvent
{
    /**
     * On connect
     */
    public const CONNECT = 'swoft.tcp.server.connect';

    /**
     * On receive
     */
    public const RECEIVE = 'swoft.tcp.server.receive';

    /**
     * On close
     */
    public const CLOSE = 'swoft.tcp.server.close';
}
