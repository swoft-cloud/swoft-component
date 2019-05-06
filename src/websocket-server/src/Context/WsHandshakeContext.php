<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Context;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;

/**
 * Class WsRequestContext - on ws handshake event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsHandshakeContext extends AbstractContext
{
    use PrototypeTrait;

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
     * @return WsHandshakeContext
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function new(Request $request, Response $response): self
    {
        /** @var self $ctx */
        $ctx = self::__instance();

        $ctx->request  = $request;
        $ctx->response = $response;

        return $ctx;
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        parent::clear();

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
}
