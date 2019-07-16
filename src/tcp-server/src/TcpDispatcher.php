<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use ReflectionException;
use ReflectionType;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Tcp\Package;
use Swoft\Tcp\Protocol;
use Swoft\Tcp\Server\Exception\CommandNotFoundException;
use Swoft\Tcp\Server\Exception\TcpUnpackingException;
use Swoft\Tcp\Server\Router\Router;
use Throwable;
use function server;

/**
 * Class TcpDispatcher
 *
 * @since 2.0.3
 * @Bean("tcpDispatcher")
 */
class TcpDispatcher
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     * @throws TcpUnpackingException
     * @throws CommandNotFoundException
     */
    public function dispatch(Request $request, Response $response): Response
    {
        /** @var Protocol $protocol */
        $protocol = Swoft::getBean('tcpServerProtocol');

        try {
            $package = $protocol->unpack($request->getRawData());
        } catch (Throwable $e) {
            throw new TcpUnpackingException("unpack request data error '{$e->getMessage()}", 500, $e);
        }

        /** @var Router $router */
        $router = Swoft::getBean('tcpRouter');
        $cmd    = $package->getCmd() ?: $router->getDefaultCommand();
        $request->setPackage($package);

        [$status, $info] = $router->match($cmd);
        if ($status === Router::NOT_FOUND) {
            throw new CommandNotFoundException("request command '{$cmd}' is not found of the tcp server");
        }

        [$ctlClass, $ctlMethod] = $info;

        server()->log("Tcp command: '{$cmd}', will call tcp request handler {$ctlClass}@{$ctlMethod}");

        $object = Swoft::getBean($ctlClass);
        $params = $this->getBindParams($ctlClass, $ctlMethod, $package, $request, $response);
        $result = $object->$ctlMethod(...$params);

        if ($result && !$result instanceof Response) {
            $response->setData($result);
        }

        return $response;
    }

    /**
     * Get method bounded params
     *
     * @param string  $class
     * @param string  $method
     * @param Package $package
     * @param Request $r
     * @param Response $w
     *
     * @return array
     * @throws ReflectionException
     */
    private function getBindParams(string $class, string $method, Package $package, Request $r, Response $w): array
    {
        $classInfo = Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string         $name
         * @var ReflectionType $paramType
         * @var mixed          $devVal
         */
        foreach ($methodParams as [$name, $paramType, $devVal]) {
            // Defined type of the param
            $type = $paramType ? $paramType->getName() : '';

            if ($type === Package::class) {
                $bindParams[] = $package;
            } elseif ($type === Request::class) {
                $bindParams[] = $r;
            } elseif ($type === Response::class) {
                $bindParams[] = $w;
            } else {
                $bindParams[] = null;
            }
        }

        return $bindParams;
    }
}
