<?php
namespace SwoftTest\Redis\Testing\Clients;

use Swoft\Redis\Redis;
use SwoftTest\Redis\Testing\Pool\TimeoutPool;
use Swoft\Bean\Annotation\Bean;

/**
 * Class TimeoutRedis
 * @Bean
 * @package SwoftTest\Redis\Testing\Clients
 */
class TimeoutRedis extends Redis
{
    /**
     * @var string
     */
    protected $poolName = TimeoutPool::class;
}