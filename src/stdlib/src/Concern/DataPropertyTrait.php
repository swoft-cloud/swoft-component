<?php declare(strict_types=1);

namespace Swoft\Stdlib\Concern;

use function array_merge;

/**
 * Trait DataPropertyTrait
 *
 * @since 2.0.6
 */
trait DataPropertyTrait
{
    /**
     * User custom data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set value to data
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        $this->data[$key] = $value;
        return true;
    }

    /**
     * Get value from data by key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Unset key
     *
     * @param string $key
     */
    public function unset(string $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * Check if an item exists in an array
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Set multi value to context
     *
     * @param array $map
     * [key => value]
     *
     * @return bool
     */
    public function setMulti(array $map): bool
    {
        $this->data = array_merge($this->data, $map);
        return true;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
