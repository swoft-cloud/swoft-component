<?php declare(strict_types=1);


namespace Swoft\Http\Server\Middleware;


use App\Controller\TestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Formatter\AcceptResponseFormatter;
use Swoft\Http\Server\Response;

/**
 * Class DefaultMiddleware
 *
 * @Bean()
 * @since 2.0
 */
class DefaultMiddleware implements MiddlewareInterface
{
    /**
     * Accept formatter
     *
     * @var AcceptResponseFormatter
     * @Inject()
     */
    private $acceptFormatter;

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->handle($request);
        $response = $this->acceptFormatter->format($response);
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function handle(ServerRequestInterface $request): Response
    {
        /* @var TestController $controller */
        $controller = \bean(TestController::class);
        $data       = $controller->test();

        // Return is not `ResponseInterface`
        if ($data instanceof ResponseInterface) {
            return $data;
        }

        $response = context()->getResponse();
        return $response->withData($data);
    }
}