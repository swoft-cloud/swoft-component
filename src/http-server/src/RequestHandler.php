<?php declare(strict_types=1);


namespace Swoft\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Exception\HttpServerException;
use Swoft\Http\Server\Middleware\MiddlewareInterface;

/**
 * Class RequestHandler
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var string
     */
    protected $defaultMiddleware = '';

    /**
     * Current offset
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * Initialize
     *
     * @param array  $middlewares
     * @param string $defaultMiddleware
     * @throws HttpServerException
     */
    public function initialize(array $middlewares, string $defaultMiddleware): void
    {
        $this->middlewares = $middlewares;

        if (!$defaultMiddleware) {
            throw new HttpServerException('You must define default middleware!');
        }

        $this->defaultMiddleware = $defaultMiddleware;
    }

    /**
     * Handler request by middleware
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middlewareName = $this->middlewares[$this->offset] ?? '';

        // Default middleware to handle request
        if (empty($middlewareName)) {
            $middlewareName = $this->defaultMiddleware;
        }

        /* @var MiddlewareInterface $middleware */
        $middleware = \bean($middlewareName);

        // Next middleware
        $this->offset++;

        return $middleware->process($request, $this);
    }

    /**
     * Insert middleware at offset
     *
     * @param array    $middlewares
     * @param int|null $offset
     *
     * @throws HttpServerException
     */
    public function insertMiddlewares(array $middlewares, int $offset = null): void
    {
        $offset = $offset ?? $this->offset;
        if ($offset > $this->offset) {
            throw new HttpServerException('Insert middleware offset must more than ' . $this->offset);
        }

        // Insert middlewares
        \array_splice($this->middlewares, $offset, 0, $middlewares);
    }
}
