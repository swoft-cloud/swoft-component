<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\ContextInterface;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Http\Message\ServerRequest;

/**
 * Class HttpContext
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class HttpContext implements ContextInterface
{
    use DataPropertyTrait;

    /**
     * @var ServerRequest
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param ServerRequest  $request
     * @param Response $response
     */
    public function initialize(ServerRequest $request, Response $response): void
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * @return ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Clear resource
     */
    public function clear(): void
    {
        $this->data = [];
        // unset
        $this->request = $this->response = null;
    }
}
