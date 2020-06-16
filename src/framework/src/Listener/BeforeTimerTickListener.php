<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Listener;

use Swoft\Context\Context;
use Swoft\Context\TimerTickContext;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\SwoftEvent;

/**
 * Class BeforeTimerTickListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::TIMER_TICK_BEFORE)
 */
class BeforeTimerTickListener implements EventHandlerInterface
{
    /**
     * Event name
     */
    public const EVENT_NAME = 'timerTick';

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        [$timerId, $params] = $event->getParams();
        $context = TimerTickContext::new($timerId, $params);

        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => self::EVENT_NAME,
                'uri'         => (string)$timerId,
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
