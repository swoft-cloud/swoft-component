<?php declare(strict_types=1);


namespace Swoft\Context;


use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class AbstractContext
 *
 * @since 2.0
 */
abstract class AbstractContext implements ContextInterface
{
    /**
     * Context data
     *
     * @var array
     */
    private $data = [];

    /**
     * Set value to  context
     * If key is like `a.b`. Equal to set $context['a']['b'] = $value
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void
    {
        ArrayHelper::set($this->data, $key, $value);
    }

    /**
     * Get value from context
     * If key is like `a.b`. Equal to get $context['a']['b']
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return ArrayHelper::get($this->data, $key, $default);
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
