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

use JsonSerializable;
use Swoft\Stdlib\Contract\Arrayable;

/**
 * Class SessionInterface - use for TCP, WS connection or HTTP-Session session data manage
 *
 * @since 2.0
 */
interface SessionInterface extends Arrayable, JsonSerializable
{
    /**
     * @param array $metadata
     *
     * @return static
     */
    public static function newFromArray(array $metadata): self;

    /**
     * Check if an item exists in an array using "dot" notation.
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
     * Clear resource
     */
    public function clear(): void;

    /**
     * @return string
     */
    public function toString(): string;
}
