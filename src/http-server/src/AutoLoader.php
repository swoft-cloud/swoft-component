<?php

namespace Swoft\Http\Server;

use Swoft\Http\Server\Formatter\JsonFormatter;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends \Swoft\AutoLoader
{
    /**
     * Get namespace and dirs
     *
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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function coreBean(): array
    {
        return [
            'httpResponse'    => [
                'format'     => Response::FORMAT_JSON,
                'formatters' => [
                    Response::FORMAT_JSON => bean(JsonFormatter::class),
                ]
            ],
            'acceptFormatter' => [
                'formats' => [
                    JsonFormatter::CONTENT_TYPE => Response::FORMAT_JSON
                ]
            ],
            'httpServer'      => [
                'on' => [
                    SwooleEvent::REQUEST => bean(RequestListener::class)
                ]
            ]
        ];
    }
}