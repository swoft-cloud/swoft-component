<?php declare(strict_types=1);

namespace Swoft\Tcp\Server;

use Swoft\Helper\ComposerJSON;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = \dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

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
    public function beans(): array
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