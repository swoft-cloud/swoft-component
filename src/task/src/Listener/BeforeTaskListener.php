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
use Swoft\Task\Request;
use Swoft\Task\Response;
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
    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        /**
         * @var Request  $request
         * @var Response $response
         */
        [$request, $response] = $event->getParams();

        $context = TaskContext::new($request, $response);

        if (Log::getLogger()->isEnable()) {
            $uri  = sprintf('%s::%s', $request->getName(), $request->getMethod());
            $data = [
                'event'       => SwooleEvent::TASK,
                'uri'         => $uri,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
