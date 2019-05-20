<?php declare(strict_types=1);

namespace Swoft\Error\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\ErrorHandlers;
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
     *
     * @throws ContainerException
     */
    public function handle(EventInterface $event): void
    {
        /** @var ErrorHandlers $chain */
        $chain = BeanFactory::getSingleton(ErrorHandlers::class);

        // Register error handlers
        $count = ErrorRegister::register($chain);

        CLog::info('Error manager init completed(%d type, %d handler, %d exception)', $chain->getTypeCount(), $count,
            $chain->getCount());
    }
}
