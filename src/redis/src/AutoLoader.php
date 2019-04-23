<?php declare(strict_types=1);


namespace Swoft\Redis;


use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function beans(): array
    {
        return [
            'redis'      => [
                'class' => RedisDb::class,
            ],
            'redis.pool' => [
                'class'   => Pool::class,
                'redisDb' => \bean('redis')
            ]
        ];
    }
}