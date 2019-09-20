<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

/**
 * Class WsServerEvent
 *
 * @since 2.0
 */
final class WsServerEvent
{
    public const REGISTER_ROUTE = 'swoft.ws.server.router.register';

    /**
     * On before handshake
     */
    public const HANDSHAKE_BEFORE  = 'swoft.ws.server.handshake.before';

    /**
     * On websocket handshake successful
     */
    public const HANDSHAKE_SUCCESS = 'swoft.ws.server.handshake.success';

    /**
     * On websocket handshake error
     */
    public const HANDSHAKE_ERROR   = 'swoft.ws.server.handshake.error';

    /**
     * On websocket opened: before
     */
    public const OPEN_BEFORE = 'swoft.ws.server.open.after';

    /**
     * On websocket opened: after
     */
    public const OPEN_AFTER = 'swoft.ws.server.open.after';

    /**
     * On websocket opened: error
     */
    public const OPEN_ERROR = 'swoft.ws.server.open.error';

    /**
     * @deprecated Please use MESSAGE_RECEIVE instead.
     */
    public const MESSAGE_BEFORE = 'swoft.ws.server.message.receive';

    /**
     * On message receive, before handle message
     */
    public const MESSAGE_RECEIVE = 'swoft.ws.server.message.receive';

    /**
     * On before call response->send()
     */
    public const MESSAGE_SEND = 'swoft.ws.server.message.send';

    /**
     * On before push message content to client
     */
    public const MESSAGE_PUSH = 'swoft.ws.server.message.push';

    /**
     * On handle message dispatch error
     */
    public const MESSAGE_ERROR = 'swoft.ws.server.message.error';

    /**
     * On after dispatch message(after push message)
     */
    public const MESSAGE_AFTER = 'swoft.ws.server.message.after';

    public const CLOSE_BEFORE = 'swoft.ws.server.close.before';

    /**
     * @deprecated Please use CLOSE_AFTER instead.
     */
    public const AFTER_CLOSE = 'swoft.ws.server.close.after';

    public const CLOSE_AFTER = 'swoft.ws.server.close.after';

    /**
     * On handle close error
     */
    public const CLOSE_ERROR = 'swoft.ws.server.close.error';
}
