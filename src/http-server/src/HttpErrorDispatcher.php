<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use function get_class;
use function printf;
use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\ErrorHandlers;
use Swoft\Error\ErrorType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\HttpErrorHandlerInterface;
use Throwable;

/**
 * Class HttpErrorHandler
 *
 * @since 2.0
 *
 * @Bean()
 */
class HttpErrorDispatcher
{
    /**
     * @param Throwable $e
     * @param Response   $response
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function run(Throwable $e, Response $response): Response
    {
        /** @var ErrorHandlers $handlers */
        $handlers = Swoft::getSingleton(ErrorHandlers::class);

        /** @var HttpErrorHandlerInterface $handler */
        if ($handler = $handlers->matchHandler($e, ErrorType::HTTP)) {
            return $handler->handle($e, $response);
        }
        
        // TODO: debug
        printf("Http Error(no handler, %s): %s\nAt File %s line %d\nTrace:\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        return $response->withStatus(500)->withContent($e->getMessage());
    }
}
