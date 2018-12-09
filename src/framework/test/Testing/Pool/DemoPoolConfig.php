<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Testing\Pool;

use Swoft\Bean\Annotation\Bean;
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
