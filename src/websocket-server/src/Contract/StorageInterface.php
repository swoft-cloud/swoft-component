<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface StorageInterface
 *
 * @since 2.0.6
 */
interface StorageInterface
{
    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool;
}
