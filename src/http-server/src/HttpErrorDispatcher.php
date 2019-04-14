<?php

namespace Swoft\Http\Server;

use Swoft\Error\ErrorHandlers;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\HttpErrorHandlerInterface;

/**
 * Class HttpErrorHandler
 * @since 2.0
 */
class HttpErrorDispatcher
{
    /**
     * @param \Throwable $e
     * @param Response   $response
     * @return Response
     * @throws \Throwable
     */
    public function run(\Throwable $e, Response $response): Response
    {
        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);

        /** @var HttpErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::HTTP)) {
            return $handler->handle($e, $response);
        }

        return $response->withStatus(500)->withContent($e->getMessage());
    }
}
