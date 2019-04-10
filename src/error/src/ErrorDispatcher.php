<?php declare(strict_types=1);

namespace Swoft\Error;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\Contract\ErrorHandlerInterface;

/**
 * Class ErrorDispatcher
 *
 * @since 2.0
 * @Bean()
 */
class ErrorDispatcher
{
    /**
     * @param \Throwable $e
     * @param int        $type
     * @throws \Throwable
     */
    public function run(\Throwable $e, int $type = ErrorType::DEF): void
    {
        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);
        $handlers->matchHandler($e);

        /** @var ErrorHandlerInterface $handler */
        $handler = $this->defaultHandler;

        // No handlers or before add handler
        if ($this->count() === 0) {
            $handler->handle($e);
            return;
        }

        try {
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
            $handler->handle($e);
        } catch (\Throwable $t) {
            $this->defaultHandler->handle($e);
        }
    }
}
