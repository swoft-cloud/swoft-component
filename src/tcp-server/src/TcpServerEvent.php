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
     * On receive before
     * @deprecated please use RECEIVE_BEFORE
     */
    public const RECEIVE = 'swoft.tcp.server.receive';

    /**
     * On receive before
     */
    public const RECEIVE_BEFORE = 'swoft.tcp.server.receive.before';

    /**
     * On package send
     * - before call response->send()
     * @deprecated please use PACKAGE_RESPONSE instead
     */
    public const PACKAGE_SEND = 'swoft.tcp.server.package.response';

    /**
     * On package send
     * - before call response->send()
     */
    public const PACKAGE_RESPONSE = 'swoft.tcp.server.package.response';

    /**
     * On content send
     * - data has been packed, before call server->send()
     */
    public const CONTENT_SEND = 'swoft.tcp.server.content.send';

    /**
     * On receive error
     */
    public const RECEIVE_ERROR = 'swoft.tcp.server.receive.error';

    /**
     * On receive after
     */
    public const RECEIVE_AFTER = 'swoft.tcp.server.receive.after';

    /**
     * On close before
     */
    public const CLOSE = 'swoft.tcp.server.close';

    /**
     * On close error
     */
    public const CLOSE_ERROR = 'swoft.tcp.server.close.error';
}
