<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client\Concern;

use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\Exception\ConnectionPoolException;
use Swoft\Log\Error;
use Swoft\Rpc\Client\Connection;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Client\Exception\RpcResponseException;
use Swoft\Rpc\Client\Pool;
use Swoft\Rpc\Client\ReferenceRegister;
use Swoft\Rpc\Protocol;
use Swoft\Stdlib\Helper\JsonHelper;
use Throwable;

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
     * @throws ConnectionPoolException
     * @throws RpcClientException
     * @throws Throwable
     * @noinspection MagicMethodsValidityInspection
     */
    protected function __proxyCall(string $interfaceClass, string $methodName, array $params)
    {
        $poolName = ReferenceRegister::getPool(__CLASS__);
        $version = ReferenceRegister::getVersion(__CLASS__);

        /* @var Pool $pool */
        $pool = BeanFactory::getBean($poolName);

        /* @var Connection $connection */
        $connection = $pool->getConnection();
        $connection->setRelease(true);
        $packet = $connection->getPacket();

        // Ext data
        $extData  = $connection->getClient()->getExtender()->getExt();
        $protocol = Protocol::new($version, $interfaceClass, $methodName, $params, $extData);
        $reqData  = $packet->encode($protocol);

        $result  = null;
        $message = 'Rpc call failed.code=%d message=%s ' . sprintf(
            'interface=%s method=%s pool=%s version=%s',
            $interfaceClass,
            $methodName,
            $poolName,
            $version
        );

        try {
            $rawResult = $this->sendAndRecv($connection, $reqData, $message);
            $response  = $packet->decodeResponse($rawResult);

            // Check response error
            if ($respErr = $response->getError()) {
                $errCode = $respErr->getCode();
                $message = $respErr->getMessage();
                $errData = $respErr->getData();

                // Record rpc error message
                $errTpl   = 'Rpc call error!code=%d message=%s pool=%s version=%s data=%s';
                $errorMsg = sprintf($errTpl, $errCode, $message, $poolName, $version, JsonHelper::encode($errData));

                Error::log($errorMsg);

                // Only to throw message and code
                $rpcResponseException = new RpcResponseException($message, $errCode);
                // set response property
                $rpcResponseException->setRpcResponse($response);
                // throw exception
                throw $rpcResponseException;
            }

            $result = $response->getResult();
        } catch (Throwable $e) {
            throw $e;
        } finally { // NOTICE: always release resource
            $connection->release();
        }

        return $result;
    }

    /**
     * @param Connection $connection
     * @param string $data
     * @param string $message
     * @param bool $reconnect
     *
     * @return string
     * @throws RpcClientException
     */
    private function sendAndRecv(Connection $connection, string $data, string $message, bool $reconnect = false): string
    {
        // Reconnect
        if ($reconnect) {
            $connection->reconnect();
        }

        if (!$connection->send($data)) {
            if ($reconnect) {
                $message = sprintf($message, $connection->getErrCode(), $connection->getErrMsg());
                throw new RpcClientException($message);
            }

            return $this->sendAndRecv($connection, $data, $message, true);
        }

        $result = $connection->recv();
        if ($result === false || empty($result)) {
            // Has been reconnected OR receive date timeout
            if ($reconnect || $connection->getErrCode() === SOCKET_ETIMEDOUT) {
                // fix: reusing use the connection will read old response data.
                if ($connection->getErrCode() === SOCKET_ETIMEDOUT) {
                    $connection->reconnect();
                }

                $message = sprintf($message, $connection->getErrCode(), $connection->getErrMsg());
                throw new RpcClientException($message);
            }

            return $this->sendAndRecv($connection, $data, $message, true);
        }

        return $result;
    }
}
