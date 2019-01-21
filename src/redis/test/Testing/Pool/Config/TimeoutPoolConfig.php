<?php
namespace SwoftTest\Redis\Testing\Pool\Config;

use Swoft\Redis\Pool\Config\RedisPoolConfig;
use Swoft\Bean\Annotation\Bean;

/**
 * Class TimeoutPoolConfig
 * @Bean
 * @package SwoftTest\Redis\Testing\Pool\Config
 */
class TimeoutPoolConfig extends RedisPoolConfig
{
    /**
     * the time of connect timeout
     * @var int
     */
    protected $timeout = 1;

    /**
     * the addresses of connection
     * @var array
     */
    protected $uri = [
        'echo.swoft.org:6379'
    ];
}
