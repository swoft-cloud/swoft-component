<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Concern;

use Countable;
use RuntimeException;
use SplDoublyLinkedList;
use SplStack;

/**
 * Class AbstractMiddlewareChain
 *
 * @since 2.0.7
 */
abstract class AbstractMiddlewareChain implements Countable
{
    /**
     * @var SplStack
     */
    protected $stack;

    /**
     * @var bool
     */
    protected $locked = false;

    /**
     * Add middleware
     *
     * @param array ...$middles
     *
     * @throws RuntimeException
     */
    public function use(...$middles): void
    {
        $this->add(...$middles);
    }

    /**
     * Add middleware
     * This method prepends new middleware to the application middleware stack.
     *
     * @param array ...$middles                 Any callable that accepts two arguments:
     *                                          1. A Request object
     *                                          2. A Handler object
     *
     * @throws RuntimeException
     */
    public function add(...$middles): void
    {
        foreach ($middles as $middleware) {
            $this->middle($middleware);
        }
    }

    /**
     * Add middlewares
     *
     * @param array $middles
     */
    public function addMiddles(array $middles): void
    {
        foreach ($middles as $middleware) {
            $this->middle($middleware);
        }
    }

    /**
     * @param string $middleware
     *
     * @throws RuntimeException
     */
    public function middle($middleware): void
    {
        if ($this->locked) {
            throw new RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        if (null === $this->stack) {
            $this->prepareStack();
        }

        $this->stack[] = $middleware;
    }

    /**
     * @param callable|null $kernel
     *
     * @throws RuntimeException
     */
    protected function prepareStack(callable $kernel = null): void
    {
        if (null !== $this->stack) {
            throw new RuntimeException('Middleware stack can only be seeded once.');
        }

        $this->stack = new SplStack;
        $this->stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);

        if ($kernel) {
            $this->stack[] = $kernel;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->stack ? $this->stack->count() : 0;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @return SplStack
     */
    public function getStack(): SplStack
    {
        return $this->stack;
    }
}
