<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Contract;

use Swoft\Error\Contract\ErrorHandlerInterface;
use Swoft\Tcp\Server\Response;
use Throwable;

/**
 * Class TcpConnectErrorHandlerInterface
 *
 * @since 2.0
 */
interface TcpConnectErrorHandlerInterface extends ErrorHandlerInterface
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response;
}
