<?php
namespace SwoftTest\Rpc\Testing\Clients;

use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;

/**
 * Class DemoServiceClient
 * @Bean
 * @package SwoftTest\Rpc\Client\Testing\Clients
 * @method version
 */
class DemoServiceClient
{
    /**
     * @Reference
     * @var DemoServiceInterface
     */
    protected $demoService;

    public function __call($name, $arguments)
    {
        return $this->demoService->$name(...$arguments);
    }
}
