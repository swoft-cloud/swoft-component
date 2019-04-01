<?php declare(strict_types=1);

namespace Swoft\ErrorHandler;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ErrorHandlerChain
 *
 * @since 1.0
 * @Bean()
 */
class ErrorHandlerChain
{
    /**
     * @var \SplPriorityQueue
     */
    private $chains;

    /**
     * @var ErrorHandlerInterface
     */
    private $defaultHandler;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->chains = new \SplPriorityQueue();
        // add default handler
        $this->defaultHandler = new DefaultExceptionHandler();
    }

    /**
     * @param \Throwable $e
     */
    public function run(\Throwable $e): void
    {
        try {
            $chains = clone $this->getChains();

            /** @var ErrorHandlerInterface $handler */
            foreach ($chains as $handler) {
                if (!$this->match($e, $handler)) {
                    continue;
                }

                // call handler
                $handler->handle($e);

                // want stop loop
                if ($handler->isStopped()) {
                    break;
                }
            }
        } catch (\Throwable $t) {
            $this->defaultHandler->handle($e);
        }
    }

    /**
     * @param \Throwable            $e
     * @param ErrorHandlerInterface $handler
     * @return bool
     */
    public function match(\Throwable $e, ErrorHandlerInterface $handler): bool
    {
        $errorClass   = \get_class($e);
        $handlerClass = \get_class($handler);

        if ($errorClass === $handlerClass) {
            return true;
        }

        if ($e instanceof $handler) {

        }

        return true;
    }

    /**
     * Add a handler to chains
     *
     * @param ErrorHandlerInterface $handler
     * @param int                   $priority
     */
    public function addHandler(ErrorHandlerInterface $handler, int $priority = 1): void
    {
        $this->chains->insert($handler, $priority);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->chains->count();
    }

    /**
     * @return \SplPriorityQueue
     */
    public function getChains(): \SplPriorityQueue
    {
        return $this->chains;
    }

    /**
     * Clear handler chains
     *
     * @return void
     */
    public function clear(): void
    {
        $this->chains = new \SplPriorityQueue();
    }

    /**
     * @return ErrorHandlerInterface
     */
    public function getDefaultHandler(): ErrorHandlerInterface
    {
        return $this->defaultHandler;
    }

    /**
     * @param ErrorHandlerInterface $defaultHandler
     */
    public function setDefaultHandler(ErrorHandlerInterface $defaultHandler): void
    {
        $this->defaultHandler = $defaultHandler;
    }
}
