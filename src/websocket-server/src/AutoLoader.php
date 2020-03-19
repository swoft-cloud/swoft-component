<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server;

use Swoft\Helper\ComposerJSON;
use Swoft\Server\SwooleEvent;
use Swoft\SwoftComponent;
use Swoft\WebSocket\Server\Router\Router;
use Swoft\WebSocket\Server\Swoole\CloseListener;
use Swoft\WebSocket\Server\Swoole\HandshakeListener;
use Swoft\WebSocket\Server\Swoole\MessageListener;
use function bean;
use function dirname;
use function env;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
final class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return (bool)env('ENABLE_WS_SERVER', 1);
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
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            WsServerBean::SERVER     => [
                // 'class' => WebSocketServer::class,
                'port' => 18308,
                'on'   => [
                    // Enable http handle
                    // SwooleEvent::REQUEST   => \bean(RequestListener::class),
                    // For WebSocket
                    SwooleEvent::HANDSHAKE => bean(HandshakeListener::class),
                    SwooleEvent::MESSAGE   => bean(MessageListener::class),
                    SwooleEvent::CLOSE     => bean(CloseListener::class),
                    // For handle clone connection on exist multi worker
                    // SwooleEvent::PIPE_MESSAGE => bean(PipeMessageListener::class),
                ]
            ],
            WsServerBean::ROUTER     => [
                'class' => Router::class,
            ],
            WsServerBean::DISPATCHER => [
                'class' => WsDispatcher::class,
            ],
            WsServerBean::MANAGER    => [
                'prefix' => 'ws'
            ],
        ];
    }
}
