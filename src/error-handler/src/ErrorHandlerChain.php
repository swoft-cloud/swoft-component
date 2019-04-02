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
     * @var int
     */
    private $counter = 0;

    /**
     * @var array
     * [
     *  exception class => [
     *      handler class  => priority,
     *      handler class1 => priority1,
     *  ],
     *  ...
     * ]
     */
    private $handlers = [];

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

        // Add default handler
        $this->defaultHandler = new DefaultExceptionHandler();

        // Register system error handle
        $this->registerErrorHandle();
    }

    /**
     * Register system error handle
     * @throws \InvalidArgumentException
     */
    protected function registerErrorHandle(): void
    {
        \set_error_handler([$this, 'handleError']);
        \set_exception_handler([$this, 'handleException']);
        \register_shutdown_function(function () {
            if ($e = \error_get_last()) {
                $this->handleError($e['type'], $e['message'], $e['file'], $e['line']);
            }
        });
    }

    /**
     * Run error handling
     * @param int    $num
     * @param string $str
     * @param string $file
     * @param int    $line
     * @throws \InvalidArgumentException
     */
    public function handleError(int $num, string $str, string $file, int $line): void
    {
        $this->handleException(new \ErrorException($str, 0, $num, $file, $line));
    }

    /**
     * Running exception handling
     * @param \Throwable $e
     * @throws \InvalidArgumentException
     */
    public function handleException(\Throwable $e): void
    {
        $this->run($e);
    }

    /**
     * Add a handler to chains
     *
     * @param string $exceptionClass
     * @param string $handlerClass
     * @param int    $priority
     */
    public function addHandler(
        string $exceptionClass,
        string $handlerClass,
        int $priority = 0
    ): void
    {
        $this->counter++;
        $this->handlers[$exceptionClass][$handlerClass] = $priority;
        // $this->chains->insert($handler, $priority);
    }

    /**
     * @param \Throwable $e
     */
    public function run(\Throwable $e): void
    {
        // no handlers or before add handler
        if ($this->count() === 0) {
            $this->defaultHandler->handle($e);
            return;
        }

        try {
            $expClass = \get_class($e);
            if (isset($this->handlers[$expClass])) {
                $handlers = $this->handlers[$expClass];
            }

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
            // TODO ...
        }

        return true;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->counter;
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
