<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Middleware;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Code;
use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;
use Swoft\Rpc\Server\Exception\RpcServerException;
use Swoft\Rpc\Server\Router\Router;
use Swoft\Stdlib\Helper\PhpHelper;

/**
 * Class DefaultMiddleware
 *
 * @since 2.0
 *
 * @Bean()
 */
class DefaultMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     * @throws RpcServerException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        return $this->handler($request);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws RpcServerException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function handler(RequestInterface $request): ResponseInterface
    {
        $version   = $request->getVersion();
        $interface = $request->getInterface();
        $method    = $request->getMethod();
        $params    = $request->getParams();

        /* @var Router $router */
        $router = BeanFactory::getBean('serviceRouter');

        [$status, $className] = $router->match($version, $interface);

        if ($status != Router::FOUND) {
            throw new RpcServerException(
                sprintf('Route(%s-%s) is not founded!', $version, $interface),
                Code::INVALID_REQUEST
            );
        }

        $object = BeanFactory::getBean($className);
        if (!$object instanceof $interface) {
            throw new RpcServerException(
                sprintf('Object is not instanceof %s', $interface),
                Code::INVALID_REQUEST
            );
        }

        if (!method_exists($object, $method)) {
            throw new RpcServerException(
                sprintf('Method(%s::%s) is not founded!', $interface, $method),
                Code::METHOD_NOT_FOUND
            );
        }

        $data = PhpHelper::call([$object, $method], ...$params);

        $response = \context()->getResponse();
        return $response->withData($data);
    }
}