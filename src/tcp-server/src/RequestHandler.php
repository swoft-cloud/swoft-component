<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

use RuntimeException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Concern\AbstractMiddlewareChain;
use Swoft\Tcp\Server\Contract\MiddlewareInterface;
use Swoft\Tcp\Server\Contract\RequestHandlerInterface;
use Swoft\Tcp\Server\Contract\RequestInterface;
use Swoft\Tcp\Server\Contract\ResponseInterface;
use Swoft\Tcp\Server\Exception\TcpMiddlewareException;
use function is_callable;
use function is_string;

/**
 * Class RequestHandler or MiddlewareChain
 *
 * @since 2.0.7
 * @Bean(scope=Bean::PROTOTYPE)
 */
class RequestHandler extends AbstractMiddlewareChain implements RequestHandlerInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $coreHandler;

    /**
     * @param MiddlewareInterface $coreHandler
     *
     * @return RequestHandler
     */
    public static function new(MiddlewareInterface $coreHandler): self
    {
        /** @var self $self */
        $self = Swoft::getBean(self::class);

        // Set properties
        $self->coreHandler = $coreHandler;

        return $self;
    }

    /**
     * Call this method to start executing all middleware
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws TcpMiddlewareException
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        if ($this->locked) {
            throw new RuntimeException('Middleware stack can’t be start once the stack is dequeuing');
        }

        if (null === $this->stack) {
            $this->prepareStack();
        }

        $this->locked = true;

        // NOTICE: 'clone' Ensure that each call stack is complete and starts at 0
        $response = (clone $this)->handle($request);

        $this->locked = false;
        return $response;
    }

    /**
     * Do not call directly externally, internally called
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws TcpMiddlewareException
     * @internal
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        // IMPORTANT: if no middleware. this is end point of the chain.
        if ($this->stack->isEmpty()) {
            return $this->coreHandler->process($request, $this);
        }

        $middleware = $this->stack->shift();

        // If is a class name or bean name
        if (is_string($middleware)) {
            $middleware = Swoft::getBean($middleware);
        }

        if ($middleware instanceof MiddlewareInterface) {
            /** @var RequestHandlerInterface $this */
            $response = $middleware->process($request, $this);
        } elseif (is_callable($middleware)) {
            $response = $middleware($request, $this);
        } else {
            throw new TcpMiddlewareException('The middleware is not a callable.');
        }

        if (!$response instanceof ResponseInterface) {
            throw new TcpMiddlewareException('Middleware must return object and instance of ' . ResponseInterface::class);
        }

        return $response;
    }
}
