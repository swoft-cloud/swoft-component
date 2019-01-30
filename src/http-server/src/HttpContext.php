<?php declare(strict_types=1);


namespace Swoft\Http\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;

/**
 * Class HttpContext
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class HttpContext extends AbstractContext
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
     */
    public function initialize(Request $request, Response $response): void
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
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
}