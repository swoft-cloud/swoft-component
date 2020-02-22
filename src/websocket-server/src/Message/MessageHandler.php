<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Message;

use RuntimeException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Concern\AbstractMiddlewareChain;
use Swoft\WebSocket\Server\Contract\MessageHandlerInterface;
use Swoft\WebSocket\Server\Contract\MiddlewareInterface;
use Swoft\WebSocket\Server\Contract\RequestInterface;
use Swoft\WebSocket\Server\Contract\ResponseInterface;
use Swoft\WebSocket\Server\Exception\WsMiddlewareException;
use function is_callable;
use function is_string;

/**
 * Class MessageHandler
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MessageHandler extends AbstractMiddlewareChain implements MessageHandlerInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $coreHandler;

    /**
     * @param MiddlewareInterface $coreHandler
     *
     * @return self
     */
    public static function new(MiddlewareInterface $coreHandler): self
    {
        /** @var self $self */
        $self = Swoft::getBean(self::class);

        // Init properties
        $self->coreHandler = $coreHandler;

        return $self;
    }

    /**
     * Call this method to start executing all middleware
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws WsMiddlewareException
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
     * @throws WsMiddlewareException
     * @internal for middleware dispatching
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
            throw new WsMiddlewareException('The middleware is not a callable.');
        }

        if (!$response instanceof ResponseInterface) {
            throw new WsMiddlewareException('Middleware must return object and instance of \Psr\Http\Message\ResponseInterface');
        }

        return $response;
    }
}
