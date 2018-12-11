<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\RpcClient\Testing\Clients;

use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use SwoftTest\RpcClient\Testing\Lib\DemoServiceInterface;

/**
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
