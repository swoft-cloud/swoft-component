<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Exception\Handler;

use Swoft\Error\ErrorType;
use Swoft\Http\Server\Contract\HttpErrorHandlerInterface;

/**
 * Class AbstractHttpErrorHandler
 *
 * @since 2.0
 */
abstract class AbstractHttpErrorHandler implements HttpErrorHandlerInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return ErrorType::HTTP;
    }
}
