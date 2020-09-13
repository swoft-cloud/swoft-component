<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server;

use Swoft;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorType;
use Swoft\Log\Debug;
use Swoft\Rpc\Error;
use Swoft\Rpc\Server\Contract\RpcServerErrorHandlerInterface;
use Throwable;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RpcErrorDispatcher
 *
 * @since 2.0
 *
 * @Bean()
 */
class RpcErrorDispatcher
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     */
    public function run(Throwable $e, Response $response): Response
    {
        /** @var ErrorManager $handlers */
        $handlers = Swoft::getSingleton(ErrorManager::class);

        /** @var RpcServerErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::RPC)) {
            return $handler->handle($e, $response);
        }

        Debug::log(
            "Rpc Error(no handler, %s): %s\nAt File %s line %d\nTrace:\n%s",
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
