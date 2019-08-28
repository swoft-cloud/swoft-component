<?php declare(strict_types=1);

namespace Swoft\Error;

use Swoft\Error\Contract\DefaultErrorHandlerInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\PhpHelper;
use Throwable;

/**
 * Class DefaultExceptionHandler
 *
 * @since 2.0
 */
class DefaultExceptionHandler implements DefaultErrorHandlerInterface
{
    /**
     * @param Throwable $e
     *
     * @return void
     */
    public function handle(Throwable $e): void
    {
        // Log::error($e->getMessage());
        $error = PhpHelper::exceptionToString($e, 'DEFAULT HANDLER', true);

        CLog::error($error);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::DEF;
    }
}
