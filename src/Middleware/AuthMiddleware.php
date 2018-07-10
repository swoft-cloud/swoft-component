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
use Swoft\Auth\Mapping\AuthorizationParserInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * Class AuthMiddleware
 * @package Swoft\Auth\Middleware
 * @Bean()
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parser = App::getBean(AuthorizationParserInterface::class);
        if (!$parser instanceof AuthorizationParserInterface) {
            throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED, 'AuthorizationParser should implement Swoft\Auth\Mapping\AuthorizationParserInterface');
        }
        $request = $parser->parse($request);
        $response = $handler->handle($request);
        return $response;
    }
}
