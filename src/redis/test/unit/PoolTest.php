<?php declare(strict_types=1);

namespace SwoftTest\Redis\Unit;

/**
 * Class PoolTest
 *
 * @package SwoftTest\Redis\Unit
 */
class PoolTest extends TestCase
{
    public function testConnection(): void
    {
        \vdump(\bean('redis'));
    }
}
