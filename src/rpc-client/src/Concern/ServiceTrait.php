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
        $version  = ReferenceRegister::getVersion(__CLASS__);

        /* @var Pool $pool */
        $pool = BeanFactory::getBean($poolName);

        /* @var Connection $connection */
        $connection = $pool->getConnection();
        $connection->setRelease(true);
        $packet = $connection->getPacket();

        // Ext data
        $ext = $connection->getClient()->getExtender()->getExt();

        $protocol = Protocol::new($version, $interfaceClass, $methodName, $params, $ext);
        $data     = $packet->encode($protocol);

        if (!$connection->send($data)) {
            throw new RpcClientException(
                sprintf('Rpc call failed.interface=%s method=%s', $interfaceClass, $methodName)
            );
        }

        $result = $connection->recv();
        $connection->release();

        $response = $packet->decodeResponse($result);

        if ($response->getError() !== null) {
            $code      = $response->getError()->getCode();
            $message   = $response->getError()->getMessage();
            $errorData = $response->getError()->getData();

            throw new RpcClientException(
                sprintf('Rpc call error!code=%d message=%d data=%s', $code, $message, $errorData)
            );
        }

        return $response->getResult();

    }
}