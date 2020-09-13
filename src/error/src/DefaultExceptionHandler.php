<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
        // Log::error($e->getMessage()); // maybe not in co env.
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
