<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use ReflectionException;
use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Swoft\Log\Debug;
use Swoft\Tcp\Server\Contract\TcpConnectErrorHandlerInterface;
use Throwable;
use Swoft\Bean\Annotation\Mapping\Bean;

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
    public function run(Throwable $e, Response $response): Response
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var TcpConnectErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::RPC)) {
            return $handler->handle($e, $response);
        }

        Debug::log("Tcp Error(no handler, %s): %s\nAt File %s line %d\nTrace:\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        $error = Error::new($e->getCode(), $e->getMessage(), null);

        $response->setError($error);
        return $response;
    }
}
