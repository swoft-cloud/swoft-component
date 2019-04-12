<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

use Swoft\Error\Contract\ErrorHandlerInterface;
use Swoft\Http\Message\Response;

/**
 * Interface HandShakeErrorHandlerInterface
 * @since 2.0
 */
interface HandShakeErrorHandlerInterface extends ErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @param Response   $response
     * @return Response
     */
    public function handle(\Throwable $e, Response $response): Response;
}
