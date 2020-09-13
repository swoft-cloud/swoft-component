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
use Swoft\Server\Context\WorkerStopContext;
use Swoft\Server\ServerEvent;
use Swoft\Server\SwooleEvent;

/**
 * Class BeforeWorkerStopListener
 *
 * @since 2.0
 *
 * @Listener(event=ServerEvent::BEFORE_WORKER_STOP_EVENT)
 */
class BeforeWorkerStopListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        [$server, $workerId] = $event->getParams();
        $context = WorkerStopContext::new($server, $workerId);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => SwooleEvent::WORKER_STOP,
                'uri'         => (string)$workerId,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
