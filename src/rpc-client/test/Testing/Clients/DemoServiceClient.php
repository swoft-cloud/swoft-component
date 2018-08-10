<?php
namespace SwoftTest\Rpc\Testing\Clients;

use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;

/**
 * Class DemoServiceClient
 * @Bean
 * @method version
 * @method longMessage($string)
 * @method get($id)
 */
class DemoServiceClient
{
    /**
     * @Reference(name="service.demo", fallback="demoFallback", breaker="breaker")
     * @var DemoServiceInterface
     */
    protected $demoService;

    public function __call($name, $arguments)
    {
        return $this->demoService->$name(...$arguments);
    }
}
