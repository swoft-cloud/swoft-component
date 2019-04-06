<?php declare(strict_types=1);


namespace Swoft\Task\Listener;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\Task\TaskContext;
use Swoft\Task\TaskEvent;

/**
 * Class BeforeTaskListener
 *
 * @since 2.0
 *
 * @Listener(event=TaskEvent::BEFORE_TASK)
 */
class BeforeTaskListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        [$server, $taskId, $srcWorkerId, $data] = $event->getParams();
        $context = TaskContext::new($server, $taskId, $srcWorkerId);

        if (Log::getLogger()->isEnable()) {
            $uri  = sprintf('%s::%s');
            $data = [
                'traceid'     => '',
                'spanid'      => '',
                'parentid'    => '',
                'uri'         => $uri,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }
        Context::set($context);
    }
}