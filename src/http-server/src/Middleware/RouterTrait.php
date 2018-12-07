<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Http\Server\AttributeEnum;

/**
 * Trait RouterTrait
 * @package Swoft\Http\Server\Middleware
 */
trait RouterTrait
{
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    protected function handleRouter(ServerRequestInterface $request): ServerRequestInterface
    {
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();

        /* @var \Swoft\Http\Server\Router\HandlerMapping $httpRouter */
        $httpRouter = App::getBean('httpRouter');
        $routeInfo = $httpRouter->getHandler($path, $method);

        return $request->withAttribute(AttributeEnum::ROUTER_ATTRIBUTE, $routeInfo);
    }
}
