<?php
namespace SwoftTest\Redis\Testing\Pool;

use Swoft\Redis\Pool\RedisPool;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use SwoftTest\Redis\Testing\Pool\Config\TimeoutPoolConfig;

/**
 * Class TimeoutPool
 * @Pool
 * @package SwoftTest\Redis\Testing\Pool
 */
class TimeoutPool extends RedisPool
{
    /**
     * Config
     *
     * @Inject()
     * @var TimeoutPoolConfig
     */
    protected $poolConfig;
}
