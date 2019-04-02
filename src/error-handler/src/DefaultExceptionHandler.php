<?php declare(strict_types=1);

namespace Swoft\ErrorHandler;

/**
 * Class DefaultExceptionHandler
 * @since 2.0
 */
class DefaultExceptionHandler implements ErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return void
     */
    public function handle(\Throwable $e): void
    {
        \printf(
            "(DEFAULT)Exception: %s\nAt File %s line %d\nTrace:\n%s\n",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }
}
