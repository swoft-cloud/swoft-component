<?php declare(strict_types=1);

namespace Swoft\Session;

use Swoft\Co;
use Swoft\Exception\ConnectionException;
use Swoft\WebSocket\Server\Connection;

/**
 * Class Session - sessions manage
 * @since 2.0
 */
class Session
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
     * @var SessionInterface[]
     *
     * @example
     * [
     *    'fd'  => SessionInterface,
     *    'fd2' => SessionInterface,
     *    'fd3' => SessionInterface,
     *    'sess id' => SessionInterface,
     * ]
     */
    private static $sessions = [];

    /*****************************************************************************
     * FD and CID relationship manage
     ****************************************************************************/

    /**
     * Bind current coroutine to an session
     *  In webSocket server, will bind FD and CID relationship. (should call it on handshake ok)
     *  In Http application, will bind sessId and cid relationship. (call on request)
     *
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
     * @return SessionInterface|Connection
     */
    public static function get(int $fd = 0): ?SessionInterface
    {
        $fd = $fd > 0 ? $fd : self::getBoundedFd();

        return self::$sessions[$fd] ?? null;
    }

    /**
     * Get connection by FD. if not found will throw exception.
     *
     * @param int $fd
     * @return SessionInterface|Connection
     */
    public static function mustGet(int $fd = 0): SessionInterface
    {
        $fd = $fd > 0 ? $fd : self::getBoundedFd();

        if (isset(self::$sessions[$fd])) {
            return self::$sessions[$fd];
        }

        throw new ConnectionException('connection information has been lost of the FD: ' . $fd);
    }

    /**
     * Set connection
     *
     * @param int              $fd On websocket server, context bind by fd.
     * @param SessionInterface $connection
     */
    public static function set(int $fd, SessionInterface $connection): void
    {
        self::$sessions[$fd] = $connection;
    }

    /**
     * Destroy context
     * @param int $fd
     */
    public static function destroy(int $fd = 0): void
    {
        $fd = $fd > 0 ? $fd : self::getBoundedFd();

        if (isset(self::$sessions[$fd])) {
            // clear self data.
            self::$sessions[$fd]->clear();
            unset(self::$sessions[$fd], $conn);
        }
    }

    /**
     * @return array
     */
    public static function getFdMap(): array
    {
        return self::$fdMap;
    }

    /**
     * @return SessionInterface[]
     */
    public static function getSessions(): array
    {
        return self::$sessions;
    }
}
