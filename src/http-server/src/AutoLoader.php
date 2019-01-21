<?php

namespace Swoft\Http\Server;

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
     */
    public function coreBean(): array
    {
        return [
            'httpServer' => [
                'on' => [
                    SwooleEvent::REQUEST => '${requestListener}'
                ]
            ]
        ];
    }
}