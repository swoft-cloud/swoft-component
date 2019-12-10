<?php declare(strict_types=1);

namespace Swoft\Contract;

/**
 * Interface SessionStorageInterface
 * refer the class {@see \SessionHandlerInterface}
 *
 * @since 2.0.8
 */
interface SessionStorageInterface
{
    /**
     * Read session data
     *
     * @param string $sessionId The session id to read data for.
     *
     * @return string
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     */
    public function read(string $sessionId): string;

    /**
     * Write session data
     *
     * @param string $sessionId   The session id.
     * @param string $sessionData The encoded session data. This data is a serialized
     *                            string and passing it as this parameter.
     *                            Please note sessions use an alternative serialization method.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function write(string $sessionId, string $sessionData): bool;

    /**
     * Destroy a session
     *
     * @param string $sessionId The session ID being destroyed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $sessionId): bool;

    /**
     * Whether the session exists
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function exists(string $sessionId): bool;

    /**
     * Clear all session
     *
     * @return bool
     */
    public function clear(): bool;
}
