<?php declare(strict_types=1);

namespace Swoft\WebSocket\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;

use Swoft\Context\AbstractContext;

/**
 * Class WsRequestContext - on handshake, open event
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsHandshakeContext extends AbstractContext
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Request  $request
     * @param Response $response
     * @return WsHandshakeContext
     */
    public static function new(Request $request, Response $response): self
    {
        /** @var self $ctx */
        $ctx = BeanFactory::getPrototype(__CLASS__);

        $ctx->request  = $request;
        $ctx->response = $response;

        return $ctx;
    }
}
