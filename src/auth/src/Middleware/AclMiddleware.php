<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\App;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Helper\ErrorCode;
use Swoft\Auth\Mapping\AuthServiceInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * @Bean()
 */
class AclMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @throws AuthException When AuthService missing or error, auth failure will throw this exception too.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttributes()['requestHandler'][2]['handler'] ?? '';
        $service = App::getBean(AuthServiceInterface::class);
        if (!$service instanceof AuthServiceInterface) {
            throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED, 'AuthService should implement Swoft\Auth\Mapping\AuthServiceInterface');
        }
        if (!$service->auth($requestHandler, $request)) {
            throw new AuthException(ErrorCode::ACCESS_DENIED);
        }
        $response = $handler->handle($request);
        return $response;
    }
}
