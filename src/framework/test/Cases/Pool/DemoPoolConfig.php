<?php
namespace SwoftTest\Pool;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Pool\PoolProperties;

/**
 * the config of env
 *
 * @Bean
 */
class DemoPoolConfig extends PoolProperties
{
    protected $timeout = 0.5;
}