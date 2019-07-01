<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Swoft\Log\Debug;
use Swoft\Tcp\Server\Contract\TcpConnectErrorHandlerInterface;
use Throwable;

/**
 * Class TcpErrorDispatcher
 *
 * @since 2.0
 *
 * @Bean()
 */
class TcpErrorDispatcher
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function connectError(Throwable $e): void
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var TcpConnectErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::TCP_CNT)) {
            $handler->handle($e);
            return;
        }

        $this->debugLog('Connect', $e);

        $error = Error::new($e->getCode(), $e->getMessage(), null);

        $response->setError($error);
        return $response;
    }

    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function receiveError(Throwable $e, Response $response): Response
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var Swoft\Tcp\Server\Contract\TcpReceiveErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::TCP_RCV)) {
            return $handler->handle($e, $response);
        }

        $this->debugLog('Receive', $e);

        $error = Error::new($e->getCode(), $e->getMessage(), null);

        $response->setError($error);
        return $response;
    }

    /**
     * @param Throwable $e
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function closeError(Throwable $e): void
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var Swoft\Tcp\Server\Contract\TcpCloseErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::TCP_CLS)) {
            $handler->handle($e);
            return;
        }

        $this->debugLog('Close', $e);
    }

    /**
     * @param string    $typeName
     * @param Throwable $e
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function debugLog(string $typeName, Throwable $e): void
    {
        Debug::log(
            "Tcp %s Error(no handler, %s): %s\nAt File %s line %d\nTrace:\n%s",
            $typeName,
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

    }
}
