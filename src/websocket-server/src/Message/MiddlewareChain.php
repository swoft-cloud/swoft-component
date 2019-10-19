<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Message;

use InvalidArgumentException;
use RuntimeException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Concern\AbstractMiddlewareChain;
use Swoft\WebSocket\Server\Contract\MessageHandlerInterface;
use Swoft\WebSocket\Server\Contract\MiddlewareInterface;
use Swoft\WebSocket\Server\Contract\RequestInterface;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use UnexpectedValueException;
use function is_callable;
use function is_string;

/**
 * Class MiddlewareChain
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MiddlewareChain extends AbstractMiddlewareChain implements MessageHandlerInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $coreHandler;

    /**
     * @param MiddlewareInterface $coreHandler
     *
     * @return MiddlewareChain
     */
    public static function new(MiddlewareInterface $coreHandler): self
    {
        /** @var self $self */
        $self = Swoft::getBean(self::class);

        $self->coreHandler = $coreHandler;

        return $self;
    }

    /**
     * Add middleware
     *
     * @param MiddlewareInterface[] ...$middleware
     *
     * @return $this
     * @throws RuntimeException
     */
    public function use(...$middleware): self
    {
        return $this->add(...$middleware);
    }

    /**
     * Add middleware
     * This method prepends new middleware to the application middleware stack.
     *
     * @param MiddlewareInterface[] ...$middles Any callable that accepts two arguments:
     *                                          1. A Request object
     *                                          2. A Handler object
     *
     * @return $this
     * @throws RuntimeException
     */
    public function add(...$middles): self
    {
        foreach ($middles as $middleware) {
            $this->middle($middleware);
        }

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return self
     * @throws RuntimeException
     */
    public function middle(MiddlewareInterface $middleware): self
    {
        if ($this->locked) {
            throw new RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        if (null === $this->stack) {
            $this->prepareStack();
        }

        $this->stack[] = $middleware;

        return $this;
    }

    /**
     * Call this method to start executing all middleware
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
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
     * @internal
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        // IMPORTANT: if no middleware. this is end point of the chain.
        if ($this->stack->isEmpty()) {
            return $this->coreHandler->process($request, $this);
        }

        $middleware = $this->stack->shift();

        // if is a class name or bean name
        if (is_string($middleware)) {
            // $middleware = new $middleware;
            $middleware = Swoft::getBean($middleware);
        }

        if ($middleware instanceof MiddlewareInterface) {
            /** @var MessageHandlerInterface $this */
            $response = $middleware->process($request, $this);
        } elseif (is_callable($middleware)) {
            $response = $middleware($request, $this);
        } else {
            throw new InvalidArgumentException('The middleware is not a callable.');
        }

        if (!$response instanceof ResponseInterface) {
            throw new UnexpectedValueException('Middleware must return object and instance of \Psr\Http\Message\ResponseInterface');
        }

        return $response;
    }
}
