<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server;

use Swoft\Helper\ComposerJSON;
use Swoft\Server\SwooleEvent;
use Swoft\SwoftComponent;
use Swoft\Tcp\Protocol;
use Swoft\Tcp\Server\Swoole\CloseListener;
use Swoft\Tcp\Server\Swoole\ConnectListener;
use Swoft\Tcp\Server\Swoole\ReceiveListener;
use function bean;
use function dirname;

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
        $jsonFile = dirname(__DIR__) . '/composer.json';

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
            TcpServerBean::SERVER   => [
                'port' => 18309,
                'on'   => [
                    SwooleEvent::CONNECT => bean(ConnectListener::class),
                    SwooleEvent::RECEIVE => bean(ReceiveListener::class),
                    SwooleEvent::CLOSE   => bean(CloseListener::class),
                    // For handle clone connection on exist multi worker
                    // SwooleEvent::PIPE_MESSAGE => bean(PipeMessageListener::class),
                ]
            ],
            TcpServerBean::PROTOCOL => [
                'class' => Protocol::class,
            ],
            TcpServerBean::MANAGER  => [
                'prefix' => 'tcp',
            ]
        ];
    }
}
