<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Exception\Dispatcher;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Error\ErrorHandlers;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Contract\HandShakeErrorHandlerInterface;
use Swoft\WebSocket\Server\Exception\WsModuleRouteException;

/**
 * Class HandShakeErrorDispatcher
 * @since 2.0
 * @Bean()
 */
class HandShakeErrorDispatcher
{
    /**
     * @param \Throwable $e
     * @param Response   $response
     * @return Response
     * @throws \Throwable
     */
    public function run(\Throwable $e, Response $response): Response
    {
        if ($e instanceof WsModuleRouteException) {
            return $response
                ->withStatus(404)
                ->withAddedHeader('Failure-Reason', 'Route not found');
        }

        /** @var ErrorHandlers $handlers */
        $handlers = \Swoft::getSingleton(ErrorHandlers::class);

        /** @var HandShakeErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::WS_HS)) {
            return $handler->handle($e, $response);
        }

        return $response->withStatus(500)->withContent($e->getMessage());
    }
}
