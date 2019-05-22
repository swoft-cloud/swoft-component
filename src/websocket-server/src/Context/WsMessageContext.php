<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Context;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractContext;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;
use Swoole\WebSocket\Frame;

/**
 * Class WsMessageContext - on ws message event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsMessageContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var MessageParserInterface
     */
    private $parser;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return WsMessageContext
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function new(Request $request, Response $response): self
    {
        /** @var self $ctx */
        $ctx = self::__instance();

        // Initial properties
        $ctx->request  = $request;
        $ctx->response = $response;

        return $ctx;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->request->getFd();
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->request->getFrame();
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        parent::clear();

        $this->parser   = null;
        $this->request  = null;
        $this->response = null;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * @return MessageParserInterface
     */
    public function getParser(): MessageParserInterface
    {
        return $this->parser;
    }

    /**
     * @param MessageParserInterface $parser
     */
    public function setParser(MessageParserInterface $parser): void
    {
        $this->parser = $parser;
    }
}
