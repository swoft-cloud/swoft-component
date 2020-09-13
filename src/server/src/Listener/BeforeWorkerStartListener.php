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
use Swoft\Server\Context\WorkerStartContext;
use Swoft\Server\ServerEvent;
use Swoft\Server\SwooleEvent;

/**
 * Class BeforeWorkerStartListener
 *
 * @since 2.0
 *
 * @Listener(event=ServerEvent::BEFORE_WORKER_START_EVENT)
 */
class BeforeWorkerStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        [$server, $workerId] = $event->getParams();
        $context = WorkerStartContext::new($server, $workerId);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => SwooleEvent::WORKER_START,
                'uri'         => (string)$workerId,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
