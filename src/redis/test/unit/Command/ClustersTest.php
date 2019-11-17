<?php declare(strict_types=1);


namespace SwoftTest\Redis\Unit\Command;


use Swoft\Bean\BeanFactory;
use Swoft\Limiter\Rate\RedisClusterRateLimiter;
use Swoft\Redis\Redis;
use SwoftTest\Redis\Unit\TestCase;

class ClustersTest extends TestCase
{

    public function testClusters()
    {
        /** @var $clusterLimiter RedisClusterRateLimiter */
        $clusterLimiter = BeanFactory::getBean(RedisClusterRateLimiter::class);
        $res            = $clusterLimiter->getTicket(
            [
                'key'     => 'swoft',
                'name'    => "limt",
                'rate'    => '20',
                'max'     => 30,
                'default' => 30,
            ]
        );

        var_dump($res);

    }

}
