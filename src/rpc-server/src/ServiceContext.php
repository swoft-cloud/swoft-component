<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Concern\DataPropertyTrait;
use Swoft\Context\ContextInterface;

/**
 * Class ServiceContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class ServiceContext implements ContextInterface
{
    use DataPropertyTrait, PrototypeTrait;

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
     *
     * @return ServiceContext
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Request $request, Response $response): self
    {
        $instance = self::__instance();

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
     * Clear
     */
    public function clear(): void
    {
        $this->data    = [];
        $this->request = $this->response = null;
    }
}