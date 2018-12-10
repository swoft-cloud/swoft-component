<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Defer;

/**
 * Class Defer
 * @author  huangzhhui <h@swoft.org>
 */
class Defer
{
    /**
     * @var \SplStack
     */
    protected $stack;

    /**
     * Defer constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_DELETE);
    }

    /**
     * Run the stack
     */
    public function __destruct()
    {
        $this->run();
    }

    /**
     * Add a defer call
     * e.g. $defer = new Defer(); $defer(function(){});
     *
     * @param callable $value
     */
    public function __invoke($value)
    {
        if (\is_callable($value)) {
            $this->push($value);
        }
    }

    /**
     * Add a defer call
     *
     * @param callable $value
     * @param array    $parameters
     * @return \Swoft\Defer\Defer
     */
    public function push(callable $value, array $parameters = []): self
    {
        $this->stack->push([$value, $parameters]);
        return $this;
    }

    /**
     * Run closure in stack
     */
    public function run()
    {
        while (! $this->stack->isEmpty()) {
            $current = $this->stack->pop();
            
            if (\is_callable($current[0])) {
                if (! empty($current[1])) {
                    $current[0](...$current[1]);
                } else {
                    $current[0]();
                }
            }
        }
    }
}
