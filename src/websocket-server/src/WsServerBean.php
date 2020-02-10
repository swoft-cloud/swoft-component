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
