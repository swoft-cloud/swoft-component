<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Listener;

use Swoft\Context\Context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\Log;
use Swoft\Process\Context\UserProcessContext;
use Swoft\Process\ProcessEvent;

/**
 * Class BeforeUserProcessListener
 *
 * @since 2.0
 *
 * @Listener(event=ProcessEvent::BEFORE_USER_PROCESS)
 */
class BeforeUserProcessListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        [$server, $process, $name] = $event->getParams();

        $context = UserProcessContext::new($server, $process);
        if (Log::getLogger()->isEnable()) {
            $data = [
                'event'       => sprintf('swoft.process.user.%s', (string)$name),
                'uri'         => '',
                'requestTime' => microtime(true),
            ];
            $context->setMulti($data);
        }

        Context::set($context);
    }
}
