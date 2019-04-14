<?php declare(strict_types=1);


namespace Swoft\Redis\Listener;


use Swoft\Bean\BeanFactory;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Redis\Connection\ConnectionManager;
use Swoft\SwoftEvent;

/**
 * Class CoroutineDeferListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::COROUTINE_DEFER)
 */
class CoroutineDeferListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(EventInterface $event): void
    {
        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $conManager->release();
    }
}