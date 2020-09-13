<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Error\Listener;

use Swoft;
use Swoft\Error\ErrorManager;
use Swoft\Error\ErrorRegister;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\SwoftEvent;

/**
 * Class WorkerStartListener
 *
 * @since 2.0
 * @Listener(SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var ErrorManager $chain */
        $chain = Swoft::getSingleton(ErrorManager::class);

        // Register error handlers
        $count  = ErrorRegister::register($chain);
        $msgTpl = 'Error manager init completed(%d type, %d handler, %d exception)';

        CLog::info($msgTpl, $chain->getTypeCount(), $count, $chain->getCount());
    }
}
