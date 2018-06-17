<?php

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
    public function map(\Closure $closure)
    {
        $chains = clone $this->getChains();
        foreach ($chains as $handler) {
            $result = $closure($handler);
            if ($result) {
                break;
            }
        }
        return $result;
    }

    /**
     * Add a handler to chains
     *
     * @param object $handler
     * @param int $priority
     */
    public function addHandler($handler, $priority = 1)
    {
        $this->chains->insert($handler, $priority);
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