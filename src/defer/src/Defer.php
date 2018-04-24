<?php

namespace Swoft\Defer;

/**
 * @uses    Defer
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
     * @return $this
     */
    public function push(callable $value): self
    {
        $this->stack->push($value);
        return $this;
    }

    /**
     * Run closure in stack
     */
    public function run()
    {
        while (! $this->stack->isEmpty()) {
            $current = $this->stack->pop();
            if (\is_callable($current)) {
                $current();
            }
        }
    }

}