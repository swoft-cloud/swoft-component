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

namespace Swoft\Devtool\Listener;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Bean\Annotation\Value;
use Swoft\Console\Helper\ConsoleUtil;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class EventFireListener
 * @package Swoft\Devtool\Listener
 * @Listener("*")
 */
class EventFireListener implements EventHandlerInterface
{
    /**
     * @Value("${config.devtool.logEventToConsole}")
     * @var bool
     */
    public $logEventToConsole = false;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        if (!$this->logEventToConsole) {
            return;
        }

        ConsoleUtil::log(
            \sprintf('Trigger the event <cyan>%s</cyan>', $event->getName()),
            [],
            'debug',
            [
                'Application',
                'WorkerId' => App::getWorkerId()
            ]
        );
    }
}
