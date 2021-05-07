<?php declare(strict_types=1);

namespace SwoftTest\Redis\Unit\Connector;

use Redis;
use Swoft\Redis\Connector\PhpRedisConnector;
use SwoftTest\Redis\Unit\RedisTestCase;
use const TEST_RDS_HOST;
use const TEST_RDS_PORT;

/**
 * Class PhpRedisConnectorTest
 *
 * @package SwoftTest\Redis\Unit\Connector
 */
class PhpRedisConnectorTest extends RedisTestCase
{
    public $config = [
        'host'           => TEST_RDS_HOST,
        'port'           => TEST_RDS_PORT,
        'database'       => 0,
        'retry_interval' => 10,
        'read_timeout'   => 0,
        'timeout'        => 2,
        // 'password'      => '123$~@456',
    ];

    public $option = [
        'prefix' => 'swoft-t_x',
    ];

    public function testConnect(): void
    {
        $prc = \bean(PhpRedisConnector::class);
        $rds = $prc->connect($this->config, $this->option);

        $this->doTestGetSet($rds);
    }

    public function testConnect_withReadTimeout(): void
    {
        $config = $this->config;

        $config['read_timeout'] = 10;

        $prc = new PhpRedisConnector;
        $rds = $prc->connect($config, $this->option);

        self::assertSame((float)$config['read_timeout'], $rds->getOption(Redis::OPT_READ_TIMEOUT));

        $this->doTestGetSet($rds);
    }

    public function testRawRedis(): void
    {
        $r1 = new Redis();
        $ok = $r1->connect($this->config['host'], $this->config['port']);

        self::assertTrue($ok);
        self::assertSame((float)$this->config['read_timeout'], $r1->getOption(Redis::OPT_READ_TIMEOUT));

        $this->doTestGetSet($r1);
    }

    protected function doTestGetSet(Redis $rds): void
    {
        $key = $this->uniqId();
        $val = $rds->get($key);
        self::assertFalse($val);

        $ok = $rds->set($key, 'val');
        $val = $rds->get($key);
        self::assertTrue($ok);
        self::assertSame('val', $val);
    }

}
