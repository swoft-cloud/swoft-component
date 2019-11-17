<?php declare(strict_types=1);

namespace Swoft\Http\Server\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;

/**
 * Class MiddlewareInterface
 *
 * @since 2.0
 */
interface MiddlewareInterface extends \Psr\Http\Server\MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request  $request
     * @param RequestHandlerInterface|Response $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
