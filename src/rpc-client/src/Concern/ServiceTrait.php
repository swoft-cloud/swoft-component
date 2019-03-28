<?php declare(strict_types=1);


namespace Swoft\Rpc\Client\Concern;

use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Client\Connection;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Client\Pool;
use Swoft\Rpc\Client\ReferenceRegister;
use Swoft\Rpc\Protocol;

/**
 * Class ServiceTrait
 *
 * @since 2.0
 */
trait ServiceTrait
{

    /**
     * @param string $interfaceClass
     * @param string $methodName
     * @param array  $params
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Connection\Pool\Exception\ConnectionPoolException
     * @throws \Swoft\Rpc\Client\Exception\RpcClientException
     */
    private function __proxyCall(string $interfaceClass, string $methodName, array $params)
    {
        $poolName = ReferenceRegister::getPool(__CLASS__);

        /* @var Pool $pool */
        $pool = BeanFactory::getBean($poolName);

        /* @var Connection $connection */
        $connection = $pool->getConnection();
        $packet     = $connection->getPacket();

        $ext = [

        ];

        $protocol = Protocol::new($interfaceClass, $methodName, $params, $ext);
        $data     = $packet->encode($protocol);

        if ($connection->send($data)) {
            $result = $connection->recv();
            return [$result];
        }

        throw new RpcClientException(
            sprintf('Rpc call failed.interface=%s method=%s', $interfaceClass, $methodName)
        );
    }
}