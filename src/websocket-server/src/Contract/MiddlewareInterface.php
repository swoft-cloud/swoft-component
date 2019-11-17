<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;

/**
 * Interface MiddlewareInterface
 *
 * @since 2.0
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface|Request        $request
     * @param MessageHandlerInterface $handler
     *
     * @return ResponseInterface|Response
     */
    public function process(RequestInterface $request, MessageHandlerInterface $handler): ResponseInterface;
}
