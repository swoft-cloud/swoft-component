<?php

namespace Swoft\Tcp\Server;

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
            'tcpServer' => [
                'on' => [
                    SwooleEvent::CONNECT      => '${connectListener}',
                    SwooleEvent::CLOSE        => '${closeListener}',
                    SwooleEvent::RECEIVE      => '${tcpReceiveListener}',
                    SwooleEvent::BUFFER_EMPTY => '${bufferEmptyListener}',
                    SwooleEvent::BUFFER_FULL  => '${bufferFullListener}',
                ]
            ]
        ];
    }
}