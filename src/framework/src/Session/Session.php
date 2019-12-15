<?php declare(strict_types=1);

namespace Swoft\Session;

use Swoft;
use Swoft\Co;
use Swoft\Contract\SessionInterface;
use Swoft\WebSocket\Server\Connection;

/**
 * Class Session - Global long connection session manager(use for ws,tcp)
 *
 * @since 2.0
 */
final class Session
{
    // The global session manager and storage bean name
    public const ManagerBean = 'gSessionManager';
    public const StorageBean = 'gSessionStorage';

    /**
     * The map for coroutineID to SessionID
     *
     * @var array [ CID => SID ]
     */
    private static $idMap = [];

    /**
     * @return SessionManager
     */
    public static function getManager(): SessionManager
    {
        return Swoft::getBean(self::ManagerBean);
    }

    /*****************************************************************************
     * SID and CID relationship manage
     ****************************************************************************/

    /**
     * Bind current coroutine to an session
     *  In webSocket server, will bind FD and CID relationship. (should call it on handshake, message, open, close)
     *  In Http application, will bind session Id and cid relationship. (call on request)
     *
     * @param string $sid
     */
    public static function bindCo(string $sid): void
    {
        self::$idMap[Co::tid()] = $sid;
    }

    /**
     * Unbind SID and CID relationship. (should call it on complete OR error)
     *
     * @return string
     */
    public static function unbindCo(): string
    {
        $sid = '';
        $tid = Co::tid();

        if (isset(self::$idMap[$tid])) {
            $sid = self::$idMap[$tid];
            unset(self::$idMap[$tid]);
        }

        return $sid;
    }

    /**
     * @return string
     */
    public static function getBoundedSid(): string
    {
        $tid = Co::tid();
        return self::$idMap[$tid] ?? '';
    }

    /*****************************************************************************
     * Session manage
     ****************************************************************************/

    /**
     * Check session has exist on current worker
     *
     * @param string $sid
     *
     * @return bool
     */
    public static function has(string $sid): bool
    {
        return self::getManager()->has($sid);
    }

    /**
     * Get session by FD
     *
     * @param string $sid If not specified, return the current corresponding session
     *
     * @return SessionInterface|Connection
     */
    public static function get(string $sid = ''): ?SessionInterface
    {
        $sid = $sid ?: self::getBoundedSid();

        return self::getManager()->get($sid);
    }

    /**
     * Get current connection by bounded FD. if not found will throw exception.
     *
     * @return SessionInterface|Connection
     */
    public static function current(): SessionInterface
    {
        $sid = self::getBoundedSid();

        return self::getManager()->mustGet($sid);
    }

    /**
     * Get connection by FD. if not found will throw exception.
     * NOTICE: recommend use Session::current() instead of the method.
     *
     * @param string $sid
     *
     * @return SessionInterface|Connection
     */
    public static function mustGet(string $sid = ''): SessionInterface
    {
        $sid = $sid ?: self::getBoundedSid();

        return self::getManager()->mustGet($sid);
    }

    /**
     * Set Session connection
     *
     * @param string           $sid On websocket server, sid is connection fd.
     * @param SessionInterface $session
     */
    public static function set(string $sid, SessionInterface $session): void
    {
        // self::$sessions[$sid] = $session;
        self::getManager()->set($sid, $session);

        // Bind cid => sid(fd)
        self::bindCo($sid);
    }

    /**
     * Destroy session by sessionId
     *
     * @param string $sid If empty, destroy current CID relationship session
     *
     * @return bool
     */
    public static function destroy(string $sid): bool
    {
        return self::getManager()->destroy($sid);
    }

    /**
     * Clear all
     */
    public static function clear(): void
    {
        self::$idMap = [];

        self::getManager()->clear();
    }

    /**
     * Clear all caches
     */
    public static function clearCaches(): void
    {
        self::$idMap = [];

        self::getManager()->clearCaches();
    }

    /**
     * @return array
     */
    public static function getIdMap(): array
    {
        return self::$idMap;
    }

    /**
     * Only get all sessions in current worker memory.
     *
     * @return SessionInterface[]
     */
    public static function getSessions(): array
    {
        return self::getManager()->getCaches();
    }
}
