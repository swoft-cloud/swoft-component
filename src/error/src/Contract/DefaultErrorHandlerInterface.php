<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Error\Contract;

use Throwable;

/**
 * Interface DefaultErrorHandlerInterface
 *
 * @since 2.0
 */
interface DefaultErrorHandlerInterface extends ErrorHandlerInterface
{
    /**
     * @param Throwable $e
     *
     * @return void
     */
    public function handle(Throwable $e): void;
}
