<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Contract;

use Swoft\Error\Contract\ErrorHandlerInterface;
use Swoft\Http\Message\Response;
use Throwable;

/**
 * Class HttpErrorHandlerInterface
 *
 * @since 2.0
 */
interface HttpErrorHandlerInterface extends ErrorHandlerInterface
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response;
}
