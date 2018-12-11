<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Server\Event\Listeners;

use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Rpc\Server\Event\RpcServerEvent;

/**
 * Event before RPC request
 * @Listener(RpcServerEvent::BEFORE_RECEIVE)
 */
class BeforeReceiveListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $params = $event->getParams();

        if (!isset($params[0])) {
            return;
        }

        $data = $params[0];
        $logId = $data['logid'] ?? uniqid('', true);
        $spanId = $data['spanid'] ?? 0;
        $uri = $data['func'] ?? 'null';

        $contextData = [
            'logid'       => $logId,
            'spanid'      => $spanId,
            'uri'         => $uri,
            'requestTime' => microtime(true),
        ];
        RequestContext::setContextData($contextData);
    }
}
