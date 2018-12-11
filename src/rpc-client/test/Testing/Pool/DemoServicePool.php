<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\RpcClient\Testing\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Rpc\Client\Pool\ServicePool as SwoftServicePool;
use SwoftTest\RpcClient\Testing\Pool\Config\DemoServicePoolConfig;

/**
 * Class DemoServicePool
 * @Pool(name="service.demo")
 * @package SwoftTest\Rpc\Testing\Pool
 */
class DemoServicePool extends SwoftServicePool
{
    /**
     * @Inject
     * @var DemoServicePoolConfig
     */
    protected $poolConfig;
}
