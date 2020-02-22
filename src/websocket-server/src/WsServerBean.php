<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server;

use Swoft\WebSocket\Server\Router\Router;

/**
 * Class WsServerBean
 *
 * @since 2.0.8
 */
final class WsServerBean
{
    /**
     * @see WebSocketServer
     */
    public const SERVER = 'wsServer';

    /**
     * @see Router
     */
    public const ROUTER = 'wsRouter';

    /**
     * @see Connection
     */
    public const CONNECTION = 'wsConnection';

    /**
     * @see WsDispatcher
     */
    public const DISPATCHER = 'wsDispatcher';

    /**
     * @see WsMessageDispatcher
     */
    public const MSG_DISPATCHER = 'wsMsgDispatcher';

    /**
     * @see ConnectionManager
     */
    public const MANAGER = 'wsConnectionManager';

    public const REQUEST = 'wsRequest';

    public const RESPONSE = 'wsResponse';
}
