<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Exception;

use Swoft\Error\Contract\ErrorHandlerInterface;
use Swoft\Http\Message\Response;

/**
 * Class AbstractHandShakeErrorHandler
 * @since 2.0
 */
abstract class AbstractHandShakeErrorHandler implements ErrorHandlerInterface
{
    /**
     * @param \Throwable $e
     * @return Response
     */
    abstract public function handle(\Throwable $e): Response;
}
