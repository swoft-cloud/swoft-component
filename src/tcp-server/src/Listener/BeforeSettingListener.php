<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Listener;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\ServerEvent;
use Swoft\Tcp\Protocol;
use Swoft\Tcp\Server\TcpServer;
use Swoft\Tcp\Server\TcpServerBean;
use function bean;

/**
 * Class BeforeSettingListener
 *
 * @Listener(ServerEvent::BEFORE_SETTING)
 */
class BeforeSettingListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var TcpServer $server */
        $server = $event->getTarget();

        if ($server instanceof TcpServer) {
            CLog::debug('sync tcp protocol setting from bean(tcpServerProtocol)');

            /** @var Protocol $proto */
            $proto = bean(TcpServerBean::PROTOCOL);
            $server->setSetting($proto->getConfig());
        }
    }
}
