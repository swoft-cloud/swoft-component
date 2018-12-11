<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\ErrorHandler;

/**
 * Class ErrorHandlerChain
 *
 * @package Swoft\ErrorHandler
 */
class ErrorHandlerChain
{
    /**
     * @var \SplPriorityQueue
     */
    protected $chains;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * ErrorHandlerChain constructor.
     */
    public function __construct()
    {
        $this->chains = new \SplPriorityQueue();
    }

    /**
     * @return \SplPriorityQueue
     */
    public function getChains(): \SplPriorityQueue
    {
        return $this->chains;
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function walk(\Closure $closure)
    {
        $result = null;
        if ($this->getChains()->count() > 0) {
            $chains = clone $this->getChains();
            foreach ($chains as $handler) {
                $result = $closure($handler);
                if ($result) {
                    break;
                }
            }
        }
        return $result;
    }

    public function handle($exception, \Closure $closure)
    {
        $handler = $this->getHandler(get_class($exception));
        return $closure($handler);
    }

    /**
     * Add a handler to chains
     *
     * @param object $handler
     * @param int $priority
     */
    public function addHandler($handler, $exception, $priority = 1)
    {
        $this->handlers[$exception] = $handler;
        $this->chains->insert($handler, $priority);
    }

    /**
     * Get a handler by exception
     * @author limx
     * @param string $exception
     * @return array
     */
    public function getHandler($exception)
    {
        $handler = $this->handlers[$exception] ?? null;

        while (!is_array($handler)) {
            $ref = new \ReflectionClass($exception);
            $parentClass = $ref->getParentClass();
            if ($parentClass) {
                $exception = get_class($parentClass);
                $handler = $this->handlers[$exception] ?? null;
            } else {
                $handler = $this->handlers[\Exception::class] ?? null;
                break;
            }
        }

        return $handler;
    }

    /**
     * Clear handler chains
     *
     * @return void
     */
    public function clear()
    {
        $this->chains = new \SplPriorityQueue();
    }
}
