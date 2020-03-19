<?php declare(strict_types=1);

namespace Swoft\Rpc\Server\Middleware;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;
use Swoft\Rpc\Server\Request;
use Swoft\Rpc\Server\Router\Router;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\ValidateRegister;
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
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     * @throws ValidatorException
     */
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        [$status, $className] = $request->getAttribute(Request::ROUTER_ATTRIBUTE);
        if ($status !== Router::FOUND) {
            return $requestHandler->handle($request);
        }

        $method    = $request->getMethod();
        $paramsMap = $request->getParamsMap();
        $validates = ValidateRegister::getValidates($className, $method);
        if (empty($validates)) {
            return $requestHandler->handle($request);
        }

        /* @var Validator $validator */
        $validator = BeanFactory::getBean('validator');
        [$paramsMap] = $validator->validateRequest($paramsMap, $validates);

        $request = $request->withParams(array_values($paramsMap));
        return $requestHandler->handle($request);
    }
}
