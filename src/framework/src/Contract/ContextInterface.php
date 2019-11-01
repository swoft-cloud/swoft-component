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
     */
    public function set(string $key, $value): void;

    /**
     * Set multi value to context
     *
     * @param array $map
     * [key => value]
     */
    public function setMulti(array $map): void;

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
