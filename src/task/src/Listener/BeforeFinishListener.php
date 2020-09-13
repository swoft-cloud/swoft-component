<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Listener;

use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\Server\SwooleEvent;
use Swoft\Task\FinishContext;
use Swoft\Task\TaskEvent;

/**
 * Class BeforeFinishListener
 *
 * @since 2.0
 *
 * @Listener(TaskEvent::BEFORE_FINISH)
 */
class BeforeFinishListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        [$server, $taskId, $data] = $event->getParams();

        $context = FinishContext::new($server, $taskId, $data);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => SwooleEvent::FINISH,
                'uri'         => $context->getTaskUniqid(),
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
