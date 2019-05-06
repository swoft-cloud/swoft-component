<?php declare(strict_types=1);

namespace Swoft\Error;

use function get_class;
use function printf;
use Swoft\Error\Contract\DefaultErrorHandlerInterface;
use Throwable;

/**
 * Class DefaultExceptionHandler
 * @since 2.0
 */
class DefaultExceptionHandler implements DefaultErrorHandlerInterface
{
    /**
     * @param Throwable $e
     * @return void
     */
    public function handle(Throwable $e): void
    {
        printf(
            "(DEFAULT HANDLER)Exception(%s): %s\nAt File %s line %d\nTrace:\n%s\n",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::DEF;
    }
}
