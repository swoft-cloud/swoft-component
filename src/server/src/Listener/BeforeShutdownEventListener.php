<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Listener;

use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\Server\Context\ShutdownContext;
use Swoft\Server\ServerEvent;
use Swoft\Server\SwooleEvent;

/**
 * Class BeforeShutdownEventListener
 *
 * @since 2.0
 *
 * @Listener(event=ServerEvent::BEFORE_SHUTDOWN_EVENT)
 */
class BeforeShutdownEventListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        [$server] = $event->getParams();
        $context = ShutdownContext::new($server);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => SwooleEvent::SHUTDOWN,
                'uri'         => '',
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
