<?php

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
            "Exception: %s\nAt File %s line %d\nTrace:\n%s",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return true;
    }
}
