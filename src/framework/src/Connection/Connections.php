<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-12
 * Time: 13:11
 */

namespace Swoft\Connection;

use Swoft\Co;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\Exception\WsServerException;

/**
 * Class Connections
 * @package Swoft\Connection
 */
class Connections
{
    /**
     * The map for coroutine id to fd
     * @var array
     * [ coID => fd ]
     */
    private static $fdMap = [];

    /**
     * Connection list
     *
     * @var ConnectionInterface[]
     *
     * @example
     * [
     *    'fd' => ConnectionInterface,
     *    'fd2' => ConnectionInterface,
     *    'fd3' => ConnectionInterface,
     * ]
     */
    private static $connections = [];

    /*****************************************************************************
     * FD and CID relationship manage
     ****************************************************************************/

    /**
     * bind FD and CID relationship. (should call it on handshake ok)
     * @param int $fd
     */
    public static function bindFd(int $fd): void
    {
        self::$fdMap[Co::tid()] = $fd;
    }

    /**
     * unbind FD and CID relationship. (should call it on close OR error)
     * @return int
     */
    public static function unbindFd(): int
    {
        $fd  = 0;
        $tid = Co::tid();

        if (isset(self::$fdMap[$tid])) {
            $fd = self::$fdMap[$tid];
            unset(self::$fdMap[$tid]);
        }

        return $fd;
    }

    /**
     * @return int
     */
    public static function getBoundedFd(): int
    {
        $tid = Co::tid();
        return self::$fdMap[$tid] ?? 0;
    }

    /*****************************************************************************
     * connection manage
     ****************************************************************************/

    /**
     * Get connection by FD
     *
     * @param int $fd If not specified, return the current corresponding connection
     * @return ConnectionInterface|Connection
     */
    public static function get(int $fd = -1): ?ConnectionInterface
    {
        $fd = $fd > -1 ? $fd : self::getBoundedFd();

        return self::$connections[$fd] ?? null;
    }

    /**
     * Get connection by FD. if not found will throw exception.
     *
     * @param int $fd
     * @return ConnectionInterface|Connection
     */
    public static function mustGet(int $fd = -1): ConnectionInterface
    {
        $fd = $fd > -1 ? $fd : self::getBoundedFd();

        if (isset(self::$connections[$fd])) {
            return self::$connections[$fd];
        }

        throw new WsServerException('connection information has been lost of the FD: ' . $fd);
    }

    /**
     * Set connection
     *
     * @param int                 $fd On websocket server, context bind by fd.
     * @param ConnectionInterface $connection
     */
    public static function set(int $fd, ConnectionInterface $connection): void
    {
        self::$connections[$fd] = $connection;
    }

    /**
     * Destroy context
     * @param int $fd
     */
    public static function destroy(int $fd = -1): void
    {
        $fd = $fd > -1 ? $fd : self::getBoundedFd();

        if (isset(self::$connections[$fd])) {
            $conn = self::$connections[$fd];
            $conn->clear();

            unset(self::$connections[$fd], $conn);
        }
    }
}
