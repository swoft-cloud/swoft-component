<?php declare(strict_types=1);


namespace Swoft\Redis;

use Redis;
use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;
use function bean;

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
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'redis'      => [
                'class'  => RedisDb::class,
                'option' => [
                    'serializer' => Redis::SERIALIZER_PHP
                ],
            ],
            'redis.pool' => [
                'class'   => Pool::class,
                'redisDb' => bean('redis'),
            ]
        ];
    }
}
