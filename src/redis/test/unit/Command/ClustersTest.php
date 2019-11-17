<?php declare(strict_types=1);


namespace SwoftTest\Redis\Unit\Command;


use Swoft\Bean\BeanFactory;
use Swoft\Limiter\Rate\RedisRateLimiter;
use SwoftTest\Redis\Unit\TestCase;

class ClustersTest extends TestCase
{

    public function testClusters(): void
    {
        /** @var $clusterLimiter RedisRateLimiter */
        $clusterLimiter = BeanFactory::getBean(RedisRateLimiter::class);

        // Use hash tag compatible redis cluster
        $res = $clusterLimiter->getTicket(
            [
                'key'     => '{tag}swoft',
                'name'    => "limt",
                'rate'    => '20',
                'max'     => 30,
                'default' => 30,
            ]
        );

        $this->assertTrue($res);
    }

}
