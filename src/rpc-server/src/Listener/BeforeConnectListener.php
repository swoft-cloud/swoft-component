<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Listener;

use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\Rpc\Server\ServiceConnectContext;
use Swoft\Server\SwooleEvent;
use Swoft\Rpc\Server\ServiceServerEvent;

/**
 * Class BeforeConnectListener
 *
 * @since 2.0
 *
 * @Listener(event=ServiceServerEvent::BEFORE_CONNECT)
 */
class BeforeConnectListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        [$server, $fd, $reactorId] = $event->getParams();
        $context = ServiceConnectContext::new($server, $fd, $reactorId);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => SwooleEvent::CONNECT,
                'uri'         => (string)$fd,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
