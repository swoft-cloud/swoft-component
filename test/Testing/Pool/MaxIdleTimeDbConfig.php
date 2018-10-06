<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Db\Testing\Pool;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Db\Driver\Driver;
use Swoft\Db\Pool\Config\DbPoolProperties;

/**
 * OtherDbConfig
 *
 * @Bean()
 */
class MaxIdleTimeDbConfig extends OtherDbConfig
{
    /**
     * @var string
     */
    protected $name = 'idle.master';

    /**
     * @var int
     */
    protected $maxIdleTime = 1;
}
