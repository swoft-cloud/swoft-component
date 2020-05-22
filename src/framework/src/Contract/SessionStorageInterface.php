<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     * @param string $storageKey The storage key to read data for.
     *
     * @return string
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     */
    public function read(string $storageKey): string;

    /**
     * Write session data
     *
     * @param string $storageKey  The storage key.
     * @param string $sessionData The encoded session data. This data is a serialized
     *                            string and passing it as this parameter.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function write(string $storageKey, string $sessionData): bool;

    /**
     * Destroy a session data
     *
     * @param string $storageKey The session ID being destroyed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $storageKey): bool;

    /**
     * Whether the session exists
     *
     * @param string $storageKey
     *
     * @return bool
     */
    public function exists(string $storageKey): bool;

    /**
     * Clear all session
     *
     * @return bool
     */
    public function clear(): bool;
}
