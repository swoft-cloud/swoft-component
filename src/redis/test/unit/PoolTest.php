<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Redis\Unit;

use function bean;

/**
 * Class PoolTest
 *
 * @package SwoftTest\Redis\Unit
 */
class PoolTest extends RedisTestCase
{
    public function testConnection(): void
    {
        $rt = bean('redis')->getReadTimeout();
        $this->assertSame(0, $rt);
    }
}
