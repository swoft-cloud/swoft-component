<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
