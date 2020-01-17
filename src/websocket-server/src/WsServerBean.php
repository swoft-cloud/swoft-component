<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

/**
 * Class WsServerBean
 *
 * @since 2.0.8
 */
final class WsServerBean
{
    public const SERVER = 'wsServer';

    public const ROUTER = 'wsRouter';

    public const DISPATCHER = 'wsDispatcher';

    public const CONNECTION = 'wsConnection';

    public const MSG_DISPATCHER = 'wsMsgDispatcher';

    public const MANAGER = 'wsConnectionManager';

    public const REQUEST = 'wsRequest';

    public const RESPONSE = 'wsResponse';
}
