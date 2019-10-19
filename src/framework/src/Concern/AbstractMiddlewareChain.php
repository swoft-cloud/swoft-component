<?php declare(strict_types=1);

namespace Swoft\Concern;

use RuntimeException;
use SplDoublyLinkedList;
use SplStack;

/**
 * Class AbstractMiddlewareChain
 *
 * @since 2.0.7
 */
abstract class AbstractMiddlewareChain
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
