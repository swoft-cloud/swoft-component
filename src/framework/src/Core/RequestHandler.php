<?php
 
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\App;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * request handler
 * @author    huangzhhui <huangzhwork@gmail.com>
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private $middlewares;

    /**
     * @var string
     */
    private $default;

    /**
     * @var integer
     */
    private $offset = 0;

    /**
     * RequestHandler constructor.
     *
     * @param array $middleware
     * @param string $default
     */
    public function __construct(array $middleware, string $default)
    {
        $this->middlewares = \array_unique($middleware);
        $this->default = $default;
    }

    /**
     * Process the request using the current middleware.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!empty($this->default) && empty($this->middlewares[$this->offset])) {
            $handler = App::getBean($this->default);
        } else {
            $handler = $this->middlewares[$this->offset];
            \is_string($handler) && $handler = App::getBean($handler);
        }

        if (!$handler instanceof MiddlewareInterface) {
            throw new \InvalidArgumentException('Invalid Handler. It must be an instance of MiddlewareInterface');
        }

        return $handler->process($request, $this->next());
    }

    /**
     * Insert middlewares to the next position
     *
     * @param array $middlewares
     * @param null $offset
     * @return $this
     */
    public function insertMiddlewares(array $middlewares, $offset = null): self
    {
        null === $offset && $offset = $this->offset;
        $chunkArray = \array_chunk($this->middlewares, $offset);
        $after = [];
        $before = $chunkArray[0];

        if (isset($chunkArray[1])) {
            $after = (array)$chunkArray[1];
        }

        $middlewares = \array_merge((array)$before, $middlewares, $after);
        $this->middlewares = $middlewares;

        return $this;
    }

    /**
     * Get a handler pointing to the next middleware.
     *
     * @return static
     */
    private function next()
    {
        $clone = clone $this;
        $clone->offset++;
        return $clone;
    }
}
