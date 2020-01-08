<?php declare(strict_types=1);

namespace Swoft\Contract;

/**
 * Class ContextInterface
 *
 * @since 2.0
 */
interface ContextInterface
{
    /**
     * Check if an item exists in an array
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get value from context
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Set value to context
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set(string $key, $value): bool;

    /**
     * Set multi value to context
     *
     * @param array $map
     * [key => value]
     *
     * @return bool
     */
    public function setMulti(array $map): bool;

    /**
     * Unset key
     *
     * @param string $key
     */
    public function unset(string $key): void;

    /**
     * Clear resource
     */
    public function clear(): void;
}
