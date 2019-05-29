<?php declare(strict_types=1);


namespace Swoft\Db\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Db\Pool;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\SwoftEvent;
use Swoole\Event;

/**
 * Class WorkerStopListener
 *
 * @since 2.0
 *
 * @Subscriber()
 */
class WorkerStopAndErrorListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SwooleEvent::WORKER_STOP    => 'handle',
            SwoftEvent::WORKER_SHUTDOWN => 'handle',
        ];
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        go(function () use ($event){
            $pools = BeanFactory::getBeans(Pool::class);

            /* @var Pool $pool */
            foreach ($pools as $pool) {
                $count = $pool->close();

                CLog::info('Close %d database connection on %s!', $count, $event->getName());
            }
        });

        Event::wait();
    }
}