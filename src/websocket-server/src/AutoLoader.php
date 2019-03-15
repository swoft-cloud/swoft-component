<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server;

use Swoft\Helper\ComposerJSON;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\SwoftComponent;
use Swoft\WebSocket\Server\Router\Router;
use Swoft\WebSocket\Server\Swoole\CloseListener;
use Swoft\WebSocket\Server\Swoole\HandShakeListener;
use Swoft\WebSocket\Server\Swoole\MessageListener;

/**
 * Class AutoLoader
 *
 * @since 2.0
 * @package Swoft\WebSocket\Server\Event
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return false;
    }

    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array
    {
        return [__NAMESPACE__ => __DIR__];
    }

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
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function coreBean(): array
    {
        return [
            'wsServer'     => [
                // 'class' => WebSocketServer::class,
                'on' => [
                    // http
                    // SwooleEvent::REQUEST   => \bean(RequestListener::class),
                    // websocket
                    SwooleEvent::HANDSHAKE => \bean(HandShakeListener::class),
                    SwooleEvent::MESSAGE   => \bean(MessageListener::class),
                    SwooleEvent::CLOSE     => \bean(CloseListener::class),
                ]
            ],
            'wsRouter'     => [
                'class' => Router::class,
            ],
            'wsDispatcher' => [
                'class' => Dispatcher::class,
            ],
        ];
    }
}