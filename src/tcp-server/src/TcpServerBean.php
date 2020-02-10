<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

/**
 * Class TcpServerBean
 *
 * @since 2.0.8
 */
final class TcpServerBean
{
    public const SERVER = 'tcpServer';

    public const ROUTER = 'tcpRouter';

    public const DISPATCHER = 'tcpDispatcher';

    public const CONNECTION = 'tcpConnection';

    public const PROTOCOL = 'tcpServerProtocol';

    public const MANAGER = 'tcpConnectionManager';

    public const REQUEST = 'tcpRequest';

    public const RESPONSE = 'tcpResponse';
}
