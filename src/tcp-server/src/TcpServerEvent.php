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
     * On connect error
     */
    public const CONNECT_ERROR = 'swoft.tcp.server.connect.error';

    /**
     * On connect after
     */
    public const AFTER_CONNECT = 'swoft.tcp.server.connect.after';

    /**
     * On receive
     */
    public const RECEIVE = 'swoft.tcp.server.receive';

    /**
     * On receive error
     */
    public const RECEIVE_ERROR = 'swoft.tcp.server.receive.error';

    /**
     * On receive after
     */
    public const AFTER_RECEIVE = 'swoft.tcp.server.receive.after';

    /**
     * On close
     */
    public const CLOSE = 'swoft.tcp.server.close';

    /**
     * After close
     */
    public const AFTER_CLOSE = 'swoft.tcp.server.close.after';

    /**
     * On close error
     */
    public const CLOSE_ERROR = 'swoft.tcp.server.close.error';
}
