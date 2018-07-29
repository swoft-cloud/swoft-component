<?php
namespace SwoftTest\Rpc\Testing\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Rpc\Client\Pool\ServicePool as SwoftServicePool;
use SwoftTest\Rpc\Testing\Pool\Config\DemoServicePoolConfig;

/**
 * Class DemoServicePool
 * @Pool("service.demo")
 * @package SwoftTest\Rpc\Client\Testing\Pool
 */
class DemoServicePool extends SwoftServicePool
{
    /**
     * Pool config
     *
     * @var DemoServicePoolConfig
     */
    protected $poolConfig;
}
