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

namespace Swoft\Process\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Core\RequestContext;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Process\Event\ProcessEvent;

/**
 * Before process listener
 *
 * @Listener(ProcessEvent::BEFORE_PROCESS)
 */
class BeforeProcessListener implements EventHandlerInterface
{
    /**
     * Event callback
     *
     * @param EventInterface $event Event object
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $params = $event->getParams();

        if (\count($params) < 1) {
            return;
        }

        // init
        $spanid = 0;
        $logid = uniqid('', false);

        $processName = $params[0];
        $uri = 'process-' . $processName;
        $flushInterval = 1;

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true)
        ];

        App::getLogger()->setFlushInterval($flushInterval);
        RequestContext::setContextData($contextData);

        // Log initialization
        App::getLogger()->initialize();
    }
}
