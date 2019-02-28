<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\ContextInterface;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Http\Message\Response;
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
    use DataPropertyTrait, PrototypeTrait;

    /**
     * @var ServerRequest
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * Create context replace of construct
     *
     * @param ServerRequest $request
     * @param Response      $response
     *
     * @return HttpContext
     * @throws \Swoft\Bean\Exception\PrototypeException
     */
    public static function new(ServerRequest $request, Response $response): self
    {
        $instance = self::__instance();

        $instance->request  = $request;
        $instance->response = $response;

        return $instance;
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
        // Unset data
        $this->data = [];

        //Unset request/response
        $this->request = $this->response = null;
    }
}
