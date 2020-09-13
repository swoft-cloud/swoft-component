<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Middleware;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Code;
use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;
use Swoft\Rpc\Server\Exception\RpcServerException;
use Swoft\Rpc\Server\Request;
use Swoft\Rpc\Server\Router\Router;
use Swoft\Stdlib\Helper\PhpHelper;
use function context;
use function method_exists;
use function sprintf;

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
     */
    private function handler(RequestInterface $request): ResponseInterface
    {
        $version   = $request->getVersion();
        $interface = $request->getInterface();
        $method    = $request->getMethod();
        $params    = $request->getParams();

        [$status, $className] = $request->getAttribute(Request::ROUTER_ATTRIBUTE);

        if ($status != Router::FOUND) {
            throw new RpcServerException(
                sprintf('Route(%s-%s) is not founded!', $version, $interface),
                Code::INVALID_REQUEST
            );
        }

        $object = BeanFactory::getBean($className);
        if (!$object instanceof $interface) {
            throw new RpcServerException(sprintf('Object is not instanceof %s', $interface), Code::INVALID_REQUEST);
        }

        if (!method_exists($object, $method)) {
            throw new RpcServerException(
                sprintf('Method(%s::%s) is not founded!', $interface, $method),
                Code::METHOD_NOT_FOUND
            );
        }

        $data = PhpHelper::call([$object, $method], ...$params);

        $response = context()->getResponse();
        return $response->setData($data);
    }
}
