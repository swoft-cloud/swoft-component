<?php declare(strict_types=1);

namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Context\AbstractContext;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;

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
     * Create context replace of construct
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return HttpContext
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Request $request, Response $response): self
    {
        // $instance = self::__instance();
        $instance = Container::$instance->getPrototype(__CLASS__);

        $instance->request  = $request;
        $instance->response = $response;

        return $instance;
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

    /**
     * Clear resource
     */
    public function clear(): void
    {
        // Clear data
        parent::clear();

        // Clear request/response
        $this->request = $this->response = null;
    }
}
