<?php declare(strict_types=1);

namespace Swoft\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Validator;

/**
 * Class ValidatorMiddleware
 *
 * @Bean()
 *
 * @since 2.0
 */
class ValidatorMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws ContainerException
     * @throws \ReflectionException
     * @throws ValidatorException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* @var Route $route */
        [$status, , $route] = $request->getAttribute(Request::ROUTER_ATTRIBUTE);

        if ($status !== Router::FOUND) {
            return $handler->handle($request);
        }

        // Controller and method
        $handlerId = $route->getHandler();
        [$className, $method] = \explode('@', $handlerId);

        $data = $request->getParsedBody();

        // Fix body is empty string
        $data = empty($data) ? [] : $data;

        /* @var Validator $validator*/
        $validator = BeanFactory::getBean('validator');
        $data = $validator->validate($data, $className, $method);
        $request = $request->withParsedBody($data);

        return $handler->handle($request);
    }
}
