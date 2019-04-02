<?php

namespace Swoft\ErrorHandler;

/**
 * Interface ErrorHandlerInterface
 *
 * @since 1.0
 */
interface ErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return void
     */
    public function handle(\Throwable $e): void;
}
