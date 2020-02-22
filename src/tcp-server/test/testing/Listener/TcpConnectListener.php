<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Testing\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Tcp\Server\TcpServerEvent;
use SwoftTest\Tcp\Server\Testing\TempString;

/**
 * Class TcpConnectListener
 *
 * @Listener(TcpServerEvent::CONNECT)
 */
class TcpConnectListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $evt
     */
    public function handle(EventInterface $evt): void
    {
        TempString::add($evt->getName(), 'fd', $evt->getParam(0), 'rd', $evt->getParam(1));
    }
}
