<?php declare(strict_types=1);

namespace Swoft\Error;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\Contract\ErrorHandlerInterface;

/**
 * Class ErrorHandlers
 * @since 2.0
 * @Bean()
 */
class ErrorHandlers
{
    /**
     * @var array
     * [
     *  exception class => handler class,
     *  ... ...
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
            if (!$e = \error_get_last()) {
                return;
            }

            $this->handleError($e['type'], $e['message'], $e['file'], $e['line']);
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
     * Add a handler class to chains
     *
     * @param string $exceptionClass
     * @param string $handlerClass
     * @param int    $type
     */
    public function addHandler(string $exceptionClass, string $handlerClass, int $type = ErrorType::DEF): void
    {
        $this->handlers[$type][$exceptionClass] = $handlerClass;
    }

    /**
     * @param \Throwable $e
     * @return mixed|null
     * @throws \Throwable
     */
    public function matchHandler(\Throwable $e)
    {
        // No handlers or before add handler
        if ($this->count() === 0) {
            return null;
        }

        $handler  = null;
        $errClass = \get_class($e);

        if (isset($this->handlers[$errClass])) {
            $handler = \Swoft::getSingleton($this->handlers[$errClass]);
        } else {
            foreach ($this->handlers as $exceptionClass => $handlerClass) {
                if ($e instanceof $exceptionClass) {
                    $handler = \Swoft::getSingleton($handlerClass);
                    break;
                }
            }
        }

        // Call error handler
        return $handler;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->handlers);
    }

    /**
     * Clear handler chains
     *
     * @return void
     */
    public function clear(): void
    {
        $this->handlers = [];
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
