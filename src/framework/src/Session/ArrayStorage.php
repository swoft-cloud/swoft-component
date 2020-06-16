<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Session;

use Swoft\Contract\SessionStorageInterface;

/**
 * Class ArrayStorageHandler
 *
 * NOTICE: only can use for test run, storage data will lost of on worker reload.
 *
 * @since 2.0.8
 */
class ArrayStorage implements SessionStorageInterface
{
    /**
     * Storage all sessions
     * eg [sid0 => session data, ...]
     *
     * @var array
     */
    private $map = [];

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
    public function read(string $sessionId): string
    {
        return $this->map[$sessionId] ?? '';
    }

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
    public function write(string $sessionId, string $sessionData): bool
    {
        $this->map[$sessionId] = $sessionData;

        return true;
    }

    /**
     * Destroy a session
     *
     * @param string $sessionId The session ID being destroyed.
     *
     * @return bool
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     */
    public function destroy(string $sessionId): bool
    {
        if (isset($this->map[$sessionId])) {
            unset($this->map[$sessionId]);
            return true;
        }

        return false;
    }

    /**
     * Whether the session exists
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function exists(string $sessionId): bool
    {
        return isset($this->map[$sessionId]);
    }

    /**
     * Clear all session
     *
     * @return bool
     */
    public function clear(): bool
    {
        $this->map = [];
        return true;
    }
}
